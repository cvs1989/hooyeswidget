using System;
using System.Windows.Forms;

namespace Net66.AsynchThread
{
	/// <summary>
	/// ������״̬
	/// </summary>
	public enum TaskStatus 
	{ 
		/// <summary>
		/// ����û������,�����ǹ�������û�п�ʼ������������������������ȡ����������
		/// </summary>
		Stopped, 
		/// <summary>
		/// ����û������,��������ǿ����ֹ
		/// </summary>
		Aborted,
		/// <summary>
		/// ����û������,�ڹ��������д����������ֹ
		/// </summary>
		ThrowErrorStoped,
		/// <summary>
		/// ����������
		/// </summary>
		Running, 
		/// <summary>
		/// ����ȡ������������
		/// </summary>
		CancelPending,
		/// <summary>
		/// ǿ����ֹ����������
		/// </summary>
		AbortPending

	} 

	/// <summary>
	/// ����״̬��Ϣ
	/// </summary>
	public class TaskEventArgs : EventArgs 
	{ 
		/// <summary>
		/// �������н��
		/// </summary>
		public Object     Result; 
		/// <summary>
		/// �������(0-100)
		/// </summary>
		public int        Progress; 
		/// <summary>
		/// ������״̬
		/// </summary>
		public TaskStatus Status; 
		/// <summary>
		/// ������Ϣ�ı�
		/// </summary>
		public String Message;
		/// <summary>
		/// ��������״̬��Ϣ
		/// </summary>
		/// <param name="progress">�������(0-100)</param>
		public TaskEventArgs( int progress ) 
		{ 
			this.Progress = progress; 
			this.Status   = TaskStatus.Running; 
		} 
		/// <summary>
		/// ��������״̬��Ϣ
		/// </summary>
		/// <param name="status">�����߳�״̬</param>
		public TaskEventArgs( TaskStatus status ) 
		{ 
			this.Status = status; 
		} 
		/// <summary>
		/// ��������״̬��Ϣ
		/// </summary>
		/// <param name="progress">�������(0-100)</param>
		/// <param name="result">���������м���</param>
		public TaskEventArgs( int progress,object result ) 
		{ 
			this.Progress = progress; 
			this.Status   = TaskStatus.Running; 
			this.Result   = result;
		} 
		/// <summary>
		/// ��������״̬��Ϣ
		/// </summary>
		/// <param name="status">�����߳�״̬</param>
		/// <param name="result">�������н��</param>
		public TaskEventArgs( TaskStatus status,object result ) 
		{ 
			this.Status   = status; 
			this.Result   = result;
		} 
		/// <summary>
		/// ��������״̬��Ϣ
		/// </summary>
		/// <param name="status">�����߳�״̬</param>
		/// <param name="message">��Ϣ�ı�</param>
		/// <param name="result">�������н��</param>
		public TaskEventArgs( TaskStatus status,string message ,object result ) 
		{ 
			this.Status   = status; 
			this.Message = message;
			this.Result   = result;
		} 
		/// <summary>
		/// ��������״̬��Ϣ
		/// </summary>
		/// <param name="progress">�������(0-100)</param>
		/// <param name="message">��Ϣ�ı�</param>
		/// <param name="result">���������м���</param>
		public TaskEventArgs( int progress,string message ,object result ) 
		{ 
			this.Progress = progress; 
			this.Status   = TaskStatus.Running; 
			this.Message = message;
			this.Result   = result;
		} 
		/// <summary>
		/// ��������״̬��Ϣ
		/// </summary>
		/// <param name="status">�����߳�״̬</param>
		/// <param name="progress">�������(0-100)</param>
		/// <param name="message">��Ϣ�ı�</param>
		/// <param name="result">���������м���</param>
		public TaskEventArgs( TaskStatus status,int progress,string message ,object result ) 
		{ 
			this.Status = status;
			this.Progress = progress; 
			this.Message = message;
			this.Result   = result;
		} 
	}     

	/// <summary>
	/// ����Ĺ�������(Work)��ί�нӿ�
	/// ����ֵ:��������(object[])
	/// ����ֵ:����(object)
	/// </summary>
	public delegate object TaskDelegate( params object[] args ); 

	/// <summary>
	/// �����¼���ί�нӿ�
	/// </summary>
	public delegate void TaskEventHandler( object sender, TaskEventArgs e ); 

	abstract public class Task
	{   
		/// <summary>
		/// ��������߳�(ǰ̨��UI�߳�)
		/// </summary>
		protected System.Threading.Thread _callThread = null; 
		/// <summary>
		/// �������߳�(��̨)
		/// </summary>
		protected System.Threading.Thread _workThread = null; 
		/// <summary>
		/// ������״̬
		/// </summary>
		protected TaskStatus _taskState = TaskStatus.Stopped; 
		/// <summary>
		/// �������(0-100)
		/// </summary>
		protected int _progress = -1;
		/// <summary>
		/// ���������
		/// </summary>
		protected object _result = null;
		/// <summary>
		/// ���������̳���ʱ,������쳣����
		/// </summary>
		protected Exception _exception = null;
		/// <summary>
		/// ������״̬�仯�¼�
		/// </summary>
		public event TaskEventHandler TaskStatusChanged; 
		/// <summary>
		/// ������ȱ仯�¼�
		/// </summary>
		public event TaskEventHandler TaskProgressChanged; 
		/// <summary>
		/// ���񱻵�����ǿ����ֹ�¼�
		/// </summary>
		public event TaskEventHandler TaskAbort; 
		/// <summary>
		/// ����������ִ���д��������¼�
		/// </summary>
		public event TaskEventHandler TaskThrowError; 
		/// <summary>
		/// ���񱻵�����ȡ���¼�
		/// </summary>
		public event TaskEventHandler TaskCancel; 
		#region ����
		
		/// <summary>
		/// ���������̳���ʱ,������쳣����
		/// </summary>
		public Exception Exception
		{
			get { return _exception;}
		}
		/// <summary>
		/// ��������߳�(ǰ̨��UI�߳�)
		/// </summary>
		public System.Threading.Thread CallThread
		{
			get { return _callThread;}
		}
		/// <summary>
		/// �������߳�(��̨)
		/// </summary>
		public System.Threading.Thread WordThread
		{
			get {return _workThread;}
		}
		
		/// <summary>
		/// �������(0-100)
		/// </summary>
		public int Progress
		{
			get {return _progress;} 
		}
		/// <summary>
		/// ������״̬
		/// </summary>
		public TaskStatus TaskState
		{
			get {return _taskState;}
		}
		/// <summary>
		/// ���������
		/// </summary>
		public object Result
		{
			get {return _result;}
		}

		protected bool IsStop
		{
			get
			{
				bool result = false;
				switch (_taskState)
				{
					case TaskStatus.Stopped:
					case TaskStatus.Aborted:
					case TaskStatus.ThrowErrorStoped:
						result = true;
						break;
					default:
						break;
				}
				return result;
			}
		}
		#endregion
		#region �����¼�
		/// <summary>
		/// ����������״̬�仯�¼�
		/// </summary>
		/// <param name="status">������״̬</param>
		/// <param name="result">�������������</param>
		protected void FireStatusChangedEvent(TaskStatus status, object result) 
		{ 
			if( TaskStatusChanged != null ) 
			{ 
				TaskEventArgs args = new TaskEventArgs( status,result); 
				AsyncInvoke(TaskStatusChanged,args);
			} 
		} 
 
		/// <summary>
		/// ����������ȱ仯�¼�
		/// </summary>
		/// <param name="progress">�������(0-100)</param>
		/// <param name="result">�������м�������</param>
		protected void FireProgressChangedEvent(int progress, object result) 
		{ 
			if( TaskProgressChanged != null ) 
			{ 
				TaskEventArgs args = new TaskEventArgs( progress,result); 
				AsyncInvoke(TaskProgressChanged,args);
			} 
		} 
		/// <summary>
		/// ������������ִ���з��ִ����¼�
		/// </summary>
		/// <param name="progress">�������(0-100)</param>
		/// <param name="result">�������м�������</param>
		protected void FireThrowErrorEvent(int progress, object result) 
		{ 
			if( TaskThrowError != null ) 
			{ 
				TaskEventArgs args = new TaskEventArgs( progress,result); 
				AsyncInvoke(TaskThrowError,args);
			} 
		} 
		/// <summary>
		/// ������������ȡ���¼�
		/// </summary>
		/// <param name="progress">�������(0-100)</param>
		/// <param name="result">�������м�������</param>
		protected void FireCancelEvent(int progress, object result) 
		{ 
			if( TaskCancel != null ) 
			{ 
				TaskEventArgs args = new TaskEventArgs( progress,result); 
				AsyncInvoke(TaskCancel,args);
			} 
		} 
		/// <summary>
		/// ������������ǿ����ֹ�¼�
		/// </summary>
		/// <param name="progress">�������(0-100)</param>
		/// <param name="result">�������м�������</param>
		protected void FireAbortEvent(int progress, object result) 
		{ 
			if( TaskAbort != null ) 
			{ 
				TaskEventArgs args = new TaskEventArgs( progress,result); 
				AsyncInvoke(TaskAbort,args);
			} 
		} 
		/// <summary>
		/// �첽���ùҽ��¼�ί��
		/// </summary>
		/// <param name="eventhandler">�¼����������</param>
		/// <param name="args">�¼���Ϣ</param>
		protected void AsyncInvoke(TaskEventHandler eventhandler,TaskEventArgs args)
		{
//			TaskEventHandler[] tpcs = (TaskEventHandler[])eventhandler.GetInvocationList();
			Delegate[] tpcs = eventhandler.GetInvocationList();
			foreach(TaskEventHandler tpc in tpcs)
			{
				if ( tpc.Target is System.Windows.Forms.Control ) 
				{ 
					Control targetForm = tpc.Target as System.Windows.Forms.Control; 
					targetForm.BeginInvoke( tpc,new object[] { this, args } ); 
				} 
				else 
				{ 
					tpc.BeginInvoke(this, args ,null,null); //�첽����,�����󲻹�
				}
			}		
		}
		#endregion
		#region �������̹���
		/// <summary>
		/// ��������Ĭ�ϵĹ�������
		/// [public object Work(params  object[] args )]
		/// </summary>
		/// <param name="args">����Ĳ�������</param>
		public bool StartTask( params object[] args ) 
		{ 
			return StartTask(new TaskDelegate( Work ),args); 
		} 
		/// <summary>
		/// ��������Ĺ�������
		/// ����������TaskDelegateί�нӿڵ�worker��������
		/// </summary>
		/// <param name="worker">��������</param>
		/// <param name="args">����Ĳ�������</param>
		public bool StartTask(TaskDelegate worker ,params object[] args ) 
		{ 
			bool result =false;
			lock( this ) 
			{ 
				if( IsStop && worker != null ) 
				{ 
					_result = null;
					_callThread = System.Threading.Thread.CurrentThread;
					// ��ʼ������������,�첽����,���ͻص�����
					worker.BeginInvoke( args ,new AsyncCallback( EndWorkBack ), worker ); 
					// ����������״̬
					_taskState = TaskStatus.Running; 
					// ����������״̬�仯�¼�
					FireStatusChangedEvent( _taskState, null); 
					result = true;
				} 
			} 
			return result;
		} 
		/// <summary>
		/// ����ֹͣ�������
		/// �Ƿ�ֹͣ�ɹ�,Ӧ��������״̬����TaskState�Ƿ�ΪTaskStatus.Stop
		/// </summary>
		public bool StopTask() 
		{ 
			bool result =false;
			lock( this ) 
			{ 
				if( _taskState == TaskStatus.Running ) 
				{ 
					// ����������״̬ 
					_taskState = TaskStatus.CancelPending; 
					// ����������״̬�仯�¼�
					FireStatusChangedEvent( _taskState, _result); 
					result = true;
				} 
			} 
			return result;
		} 
		/// <summary>
		/// ǿ����ֹ����Ĺ����߳�
		/// 
		/// </summary>
		public bool AbortTask() 
		{ 
			bool result = false;
			lock( this ) 
			{ 
				if( _taskState == TaskStatus.Running && _workThread != null ) 
				{ 
					if (_workThread.ThreadState != System.Threading.ThreadState.Stopped)
					{
						_workThread.Abort();
					}
					System.Threading.Thread.Sleep(2);
					if (_workThread.ThreadState == System.Threading.ThreadState.Stopped)
					{
						// ����������״̬ 
						_taskState = TaskStatus.Aborted; 
						result = true;
					}
					else
					{
						// ����������״̬ 
						_taskState = TaskStatus.AbortPending; 
						result = false;
					}
					// ����������״̬�仯�¼�
					FireStatusChangedEvent( _taskState, _result); 
				} 
			} 
			return result;
		} 

		/// <summary>
		/// ����������ɺ�Ļص�����
		/// ������Ƿ����,����ȡ�����·��ؽ��ֵ
		/// </summary>
		/// <param name="ar">�첽�����źŶ���</param>
		protected void EndWorkBack( IAsyncResult ar ) 
		{ 
			bool error = false;
			bool abort = false;
			try												//����Ƿ����
			{
				TaskDelegate del = (TaskDelegate)ar.AsyncState; 
				_result = del.EndInvoke( ar ); 
			}
			catch(Exception e)								//�������,�򱣴�������
			{
				error = true;
				_exception = e;
				if (e.GetType() == typeof(System.Threading.ThreadAbortException))
				{
					abort = true;
					FireAbortEvent(_progress,_exception);
				}
				else
				{
					FireThrowErrorEvent(_progress,_exception);
				}
			}
			lock( this ) 
			{ 
				if (error)
				{
					if ( abort)
					{
						_taskState = TaskStatus.Aborted;		//������ǿ����ֹ
					}
					else
					{
						_taskState = TaskStatus.ThrowErrorStoped;//���ִ������ֹ
					}
				} 
				else
				{	_taskState = TaskStatus.Stopped;}		  //��������
				FireStatusChangedEvent( _taskState, _result);
			} 
		} 
		#endregion

		#region ���������Ļ���
		/// <summary>
		/// ��������
		/// �ڼ̳�����Ӧ��д(override)�˷���,��ʵ�־���Ĺ�������,ע���Լ���:
		/// 1.���ڼ̳���������base.Work,�ڻ���(base)��Work������,ִ���߳���ΪIsBackground=true,�����湤���̶߳���
		/// 2.�ڼ̳�����,Ӧ��ʱ����_progress��_result����,��ʹProgress��Result����ֵ��ȷ
		/// 3.��ִ�й�����Ӧ���_taskState,��ʹ�����б�����ֹͣ��(_taskStateΪTaskStatus.CancelPending),�����߳��������ֹ.
		/// 4.���ڼ̳������¶������¼�,Ӧ�ڴ˷��������ô���
		/// 5.�����߳�״̬���ɹ�����������,�����ڹ��������в�Ӧ�ı�_taskState����ֵ
		/// 6.����������Ӧ��args����������Ч���
		/// </summary>
		/// <param name="args">����Ĳ�������</param>
		/// <returns>����null</returns>
		virtual public object Work(params  object[] args )
		{
			System.Threading.Thread.CurrentThread.IsBackground = true;
			_workThread = System.Threading.Thread.CurrentThread;
			_result = null;
			return null;
		}

		#endregion
	}
}

#region ʹ��Task��
/*

ʹ�� Task ��

һ.��UI�߳��д���Task��

Task �ฺ������̨�̡߳�Ҫʹ�� Task �࣬��������������Ǵ���һ�� Task ����ע�����������¼�������ʵ����Щ�¼��Ĵ�����Ϊ�¼����� UI �߳��ϼ����ģ��������������ص��Ĵ����е��̴߳������⡣

�����ʾ��չʾ����δ��� Task �����ּ���UI ��������ť��һ�������������㣬һ������ֹͣ���㣬����һ����������ʾ��ǰ�ļ�����ȡ�

// ��������������
_Task = new Task(); 
// �ҽ�������������״̬�仯�¼�
_Task.TaskStatusChanged += new TaskEventHandler( OnTaskStatusChanged ); 
// �ҽ����������������ȱ仯�¼�
_Task.TaskProgressChanged += new TaskEventHandler( OnTaskProgressChanged ); 

(1)
���ڼ���״̬�ͼ�������¼����¼����������Ӧ�ظ��� UI������ͨ������״̬���ؼ��� 

private void OnTaskProgressChanged( object sender,TaskEventArgs e ) 
{ 
    _progressBar.Value = e.Progress; 
} 
(2)
����Ĵ���չʾ�� TaskStatusChanged �¼����������½�������ֵ�Է�ӳ��ǰ�ļ�����ȡ��ٶ�����������Сֵ�����ֵ�Ѿ���ʼ����

private void OnTaskStatusChanged( object sender, TaskEventArgs e ) 
{ 
    switch ( e.Status ) 
    { 
        case TaskStatus.Running: 
            button1.Enabled = false; 
            button2.Enabled = true; 
            break; 
        case TaskStatus.Stop: 
            button1.Enabled = true; 
            button2.Enabled = false; 
            break; 
        case TaskStatus.CancelPending: 
            button1.Enabled = false; 
            button2.Enabled = false; 
            break; 
    } 
} 

�����ʾ���У�TaskStatusChanged �¼����������ݼ���״̬���úͽ���������ֹͣ��ť������Է�ֹ�û���������һ���Ѿ��ڽ��еļ��㣬�������û��ṩ�йؼ���״̬�ķ�����

ͨ��ʹ�� Task �����еĹ���������UI Ϊÿ����ť����ʵ���˴����¼���������Ա�������ֹͣ���㡣���磬������ť�¼����������� StartTask ������������ʾ��

private void startButton_Click( object sender, System.EventArgs e ) 
{ 
    _Task.StartTask( new object[] {} ); 
} 

���Ƶأ�ֹͣ���㰴ťͨ������ StopTask ������ֹͣ���㣬������ʾ��

private void stopButton_Click( object sender, System.EventArgs e ) 
{ 
    _Task.StopTask(); 
} 

��.�����ڷ�UI�߳���ʹ��Task��ʱ
(1)��(2)Ӧ�����¸ı�

(1)
���ڼ���״̬�ͼ�������¼����¼����������Ӧ�ظ��� UI������ͨ������״̬���ؼ��� 

private void OnTaskProgressChanged( object sender,TaskEventArgs e ) 
{ 
	if (InvokeRequired )		//����UI�߳���,�첽����
	{
		TaskEventHandler TPChanged = new TaskEventHandler( OnTaskProgressChanged ); 
		this.BeginInvoke(TPChanged,new object[] {sender,e});
	}
	else						//����
	{
		_progressBar.Value = e.Progress; 
	}
} 
(2)
����Ĵ���չʾ�� TaskStatusChanged �¼����������½�������ֵ�Է�ӳ��ǰ�ļ�����ȡ��ٶ�����������Сֵ�����ֵ�Ѿ���ʼ����

private void OnTaskStatusChanged( object sender, TaskEventArgs e ) 
{ 
	if (InvokeRequired )		//����UI�߳���,�첽����
	{
		TaskEventHandler TSChanged = new TaskEventHandler( OnTaskStatusChanged ); 
		this.BeginInvoke(TSChanged,new object[] {sender,e});
	}
	else						//����
	{
		switch ( e.Status ) 
		{ 
			case TaskStatus.Running: 
				button1.Enabled = false; 
				button2.Enabled = true; 
				break; 
			case TaskStatus.Stop: 
				button1.Enabled = true; 
				button2.Enabled = false; 
				break; 
			case TaskStatus.CancelPending: 
				button1.Enabled = false; 
				button2.Enabled = false; 
				break; 
		} 
	}
} 

*/
#endregion
