using System;
using System.Windows.Forms;

namespace Net66.AsynchThread
{
	/// <summary>
	/// 任务工作状态
	/// </summary>
	public enum TaskStatus 
	{ 
		/// <summary>
		/// 任务没有运行,可能是工作进程没有开始、工作进程正常结束或正常取消工作进程
		/// </summary>
		Stopped, 
		/// <summary>
		/// 任务没有运行,被调用者强行中止
		/// </summary>
		Aborted,
		/// <summary>
		/// 任务没有运行,在工作进程中触发错误而中止
		/// </summary>
		ThrowErrorStoped,
		/// <summary>
		/// 任务运行中
		/// </summary>
		Running, 
		/// <summary>
		/// 尝试取消工作进程中
		/// </summary>
		CancelPending,
		/// <summary>
		/// 强行中止工作进程中
		/// </summary>
		AbortPending

	} 

	/// <summary>
	/// 任务状态消息
	/// </summary>
	public class TaskEventArgs : EventArgs 
	{ 
		/// <summary>
		/// 任务运行结果
		/// </summary>
		public Object     Result; 
		/// <summary>
		/// 任务进度(0-100)
		/// </summary>
		public int        Progress; 
		/// <summary>
		/// 任务工作状态
		/// </summary>
		public TaskStatus Status; 
		/// <summary>
		/// 任务消息文本
		/// </summary>
		public String Message;
		/// <summary>
		/// 创建任务状态消息
		/// </summary>
		/// <param name="progress">任务进度(0-100)</param>
		public TaskEventArgs( int progress ) 
		{ 
			this.Progress = progress; 
			this.Status   = TaskStatus.Running; 
		} 
		/// <summary>
		/// 创建任务状态消息
		/// </summary>
		/// <param name="status">任务线程状态</param>
		public TaskEventArgs( TaskStatus status ) 
		{ 
			this.Status = status; 
		} 
		/// <summary>
		/// 创建任务状态消息
		/// </summary>
		/// <param name="progress">任务进度(0-100)</param>
		/// <param name="result">任务运行中间结果</param>
		public TaskEventArgs( int progress,object result ) 
		{ 
			this.Progress = progress; 
			this.Status   = TaskStatus.Running; 
			this.Result   = result;
		} 
		/// <summary>
		/// 创建任务状态消息
		/// </summary>
		/// <param name="status">任务线程状态</param>
		/// <param name="result">任务运行结果</param>
		public TaskEventArgs( TaskStatus status,object result ) 
		{ 
			this.Status   = status; 
			this.Result   = result;
		} 
		/// <summary>
		/// 创建任务状态消息
		/// </summary>
		/// <param name="status">任务线程状态</param>
		/// <param name="message">消息文本</param>
		/// <param name="result">任务运行结果</param>
		public TaskEventArgs( TaskStatus status,string message ,object result ) 
		{ 
			this.Status   = status; 
			this.Message = message;
			this.Result   = result;
		} 
		/// <summary>
		/// 创建任务状态消息
		/// </summary>
		/// <param name="progress">任务进度(0-100)</param>
		/// <param name="message">消息文本</param>
		/// <param name="result">任务运行中间结果</param>
		public TaskEventArgs( int progress,string message ,object result ) 
		{ 
			this.Progress = progress; 
			this.Status   = TaskStatus.Running; 
			this.Message = message;
			this.Result   = result;
		} 
		/// <summary>
		/// 创建任务状态消息
		/// </summary>
		/// <param name="status">任务线程状态</param>
		/// <param name="progress">任务进度(0-100)</param>
		/// <param name="message">消息文本</param>
		/// <param name="result">任务运行中间结果</param>
		public TaskEventArgs( TaskStatus status,int progress,string message ,object result ) 
		{ 
			this.Status = status;
			this.Progress = progress; 
			this.Message = message;
			this.Result   = result;
		} 
	}     

	/// <summary>
	/// 任务的工作方法(Work)的委托接口
	/// 传入值:对象数组(object[])
	/// 返回值:对象(object)
	/// </summary>
	public delegate object TaskDelegate( params object[] args ); 

	/// <summary>
	/// 任务事件的委托接口
	/// </summary>
	public delegate void TaskEventHandler( object sender, TaskEventArgs e ); 

	abstract public class Task
	{   
		/// <summary>
		/// 任务调用线程(前台或UI线程)
		/// </summary>
		protected System.Threading.Thread _callThread = null; 
		/// <summary>
		/// 任务工作线程(后台)
		/// </summary>
		protected System.Threading.Thread _workThread = null; 
		/// <summary>
		/// 任务工作状态
		/// </summary>
		protected TaskStatus _taskState = TaskStatus.Stopped; 
		/// <summary>
		/// 任务进度(0-100)
		/// </summary>
		protected int _progress = -1;
		/// <summary>
		/// 任务工作结果
		/// </summary>
		protected object _result = null;
		/// <summary>
		/// 任务工作进程出错时,捕获的异常对象
		/// </summary>
		protected Exception _exception = null;
		/// <summary>
		/// 任务工作状态变化事件
		/// </summary>
		public event TaskEventHandler TaskStatusChanged; 
		/// <summary>
		/// 任务进度变化事件
		/// </summary>
		public event TaskEventHandler TaskProgressChanged; 
		/// <summary>
		/// 任务被调用者强行中止事件
		/// </summary>
		public event TaskEventHandler TaskAbort; 
		/// <summary>
		/// 任务工作方法执行中触发错误事件
		/// </summary>
		public event TaskEventHandler TaskThrowError; 
		/// <summary>
		/// 任务被调用者取消事件
		/// </summary>
		public event TaskEventHandler TaskCancel; 
		#region 属性
		
		/// <summary>
		/// 任务工作进程出错时,捕获的异常对象
		/// </summary>
		public Exception Exception
		{
			get { return _exception;}
		}
		/// <summary>
		/// 任务调用线程(前台或UI线程)
		/// </summary>
		public System.Threading.Thread CallThread
		{
			get { return _callThread;}
		}
		/// <summary>
		/// 任务工作线程(后台)
		/// </summary>
		public System.Threading.Thread WordThread
		{
			get {return _workThread;}
		}
		
		/// <summary>
		/// 任务进度(0-100)
		/// </summary>
		public int Progress
		{
			get {return _progress;} 
		}
		/// <summary>
		/// 任务工作状态
		/// </summary>
		public TaskStatus TaskState
		{
			get {return _taskState;}
		}
		/// <summary>
		/// 任务工作结果
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
		#region 触发事件
		/// <summary>
		/// 触发任务工作状态变化事件
		/// </summary>
		/// <param name="status">任务工作状态</param>
		/// <param name="result">任务工作结果对象</param>
		protected void FireStatusChangedEvent(TaskStatus status, object result) 
		{ 
			if( TaskStatusChanged != null ) 
			{ 
				TaskEventArgs args = new TaskEventArgs( status,result); 
				AsyncInvoke(TaskStatusChanged,args);
			} 
		} 
 
		/// <summary>
		/// 触发任务进度变化事件
		/// </summary>
		/// <param name="progress">任务进度(0-100)</param>
		/// <param name="result">任务工作中间结果对象</param>
		protected void FireProgressChangedEvent(int progress, object result) 
		{ 
			if( TaskProgressChanged != null ) 
			{ 
				TaskEventArgs args = new TaskEventArgs( progress,result); 
				AsyncInvoke(TaskProgressChanged,args);
			} 
		} 
		/// <summary>
		/// 触发工作方法执行中发现错误事件
		/// </summary>
		/// <param name="progress">任务进度(0-100)</param>
		/// <param name="result">任务工作中间结果对象</param>
		protected void FireThrowErrorEvent(int progress, object result) 
		{ 
			if( TaskThrowError != null ) 
			{ 
				TaskEventArgs args = new TaskEventArgs( progress,result); 
				AsyncInvoke(TaskThrowError,args);
			} 
		} 
		/// <summary>
		/// 触发被调用者取消事件
		/// </summary>
		/// <param name="progress">任务进度(0-100)</param>
		/// <param name="result">任务工作中间结果对象</param>
		protected void FireCancelEvent(int progress, object result) 
		{ 
			if( TaskCancel != null ) 
			{ 
				TaskEventArgs args = new TaskEventArgs( progress,result); 
				AsyncInvoke(TaskCancel,args);
			} 
		} 
		/// <summary>
		/// 触发被调用者强行中止事件
		/// </summary>
		/// <param name="progress">任务进度(0-100)</param>
		/// <param name="result">任务工作中间结果对象</param>
		protected void FireAbortEvent(int progress, object result) 
		{ 
			if( TaskAbort != null ) 
			{ 
				TaskEventArgs args = new TaskEventArgs( progress,result); 
				AsyncInvoke(TaskAbort,args);
			} 
		} 
		/// <summary>
		/// 异步调用挂接事件委托
		/// </summary>
		/// <param name="eventhandler">事件处理方法句柄</param>
		/// <param name="args">事件消息</param>
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
					tpc.BeginInvoke(this, args ,null,null); //异步调用,启动后不管
				}
			}		
		}
		#endregion
		#region 工作进程管理
		/// <summary>
		/// 开启任务默认的工作进程
		/// [public object Work(params  object[] args )]
		/// </summary>
		/// <param name="args">传入的参数数组</param>
		public bool StartTask( params object[] args ) 
		{ 
			return StartTask(new TaskDelegate( Work ),args); 
		} 
		/// <summary>
		/// 开启任务的工作进程
		/// 将开启符合TaskDelegate委托接口的worker工作方法
		/// </summary>
		/// <param name="worker">工作方法</param>
		/// <param name="args">传入的参数数组</param>
		public bool StartTask(TaskDelegate worker ,params object[] args ) 
		{ 
			bool result =false;
			lock( this ) 
			{ 
				if( IsStop && worker != null ) 
				{ 
					_result = null;
					_callThread = System.Threading.Thread.CurrentThread;
					// 开始工作方法进程,异步开启,传送回调方法
					worker.BeginInvoke( args ,new AsyncCallback( EndWorkBack ), worker ); 
					// 更新任务工作状态
					_taskState = TaskStatus.Running; 
					// 触发任务工作状态变化事件
					FireStatusChangedEvent( _taskState, null); 
					result = true;
				} 
			} 
			return result;
		} 
		/// <summary>
		/// 请求停止任务进程
		/// 是否停止成功,应看任务工作状态属性TaskState是否为TaskStatus.Stop
		/// </summary>
		public bool StopTask() 
		{ 
			bool result =false;
			lock( this ) 
			{ 
				if( _taskState == TaskStatus.Running ) 
				{ 
					// 更新任务工作状态 
					_taskState = TaskStatus.CancelPending; 
					// 触发任务工作状态变化事件
					FireStatusChangedEvent( _taskState, _result); 
					result = true;
				} 
			} 
			return result;
		} 
		/// <summary>
		/// 强行中止任务的工作线程
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
						// 更新任务工作状态 
						_taskState = TaskStatus.Aborted; 
						result = true;
					}
					else
					{
						// 更新任务工作状态 
						_taskState = TaskStatus.AbortPending; 
						result = false;
					}
					// 触发任务工作状态变化事件
					FireStatusChangedEvent( _taskState, _result); 
				} 
			} 
			return result;
		} 

		/// <summary>
		/// 工作方法完成后的回调方法
		/// 将检查是否出错,并获取、更新返回结果值
		/// </summary>
		/// <param name="ar">异步调用信号对象</param>
		protected void EndWorkBack( IAsyncResult ar ) 
		{ 
			bool error = false;
			bool abort = false;
			try												//检查是否错误
			{
				TaskDelegate del = (TaskDelegate)ar.AsyncState; 
				_result = del.EndInvoke( ar ); 
			}
			catch(Exception e)								//如果错误,则保存错误对象
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
						_taskState = TaskStatus.Aborted;		//调用者强行中止
					}
					else
					{
						_taskState = TaskStatus.ThrowErrorStoped;//出现错误而中止
					}
				} 
				else
				{	_taskState = TaskStatus.Stopped;}		  //正常结束
				FireStatusChangedEvent( _taskState, _result);
			} 
		} 
		#endregion

		#region 工作方法的基础
		/// <summary>
		/// 工作方法
		/// 在继承类中应重写(override)此方法,以实现具体的工作内容,注意以几点:
		/// 1.须在继承类是引用base.Work,在基类(base)的Work方法中,执行线程设为IsBackground=true,并保存工作线程对象
		/// 2.在继承类中,应及时更新_progress与_result对象,以使Progress和Result属性值正确
		/// 3.在执行过程中应检查_taskState,以使任务中被请求停止后(_taskState为TaskStatus.CancelPending),工作线程能最快终止.
		/// 4.如在继承类中新定义了事件,应在此方法中引用触发
		/// 5.工作线程状态不由工作方法管理,所以在工作方法中不应改变_taskState变量值
		/// 6.工作方法中应对args参数进行有效检查
		/// </summary>
		/// <param name="args">传入的参数数组</param>
		/// <returns>返回null</returns>
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

#region 使用Task类
/*

使用 Task 类

一.在UI线程中创建Task类

Task 类负责管理后台线程。要使用 Task 类，必须做的事情就是创建一个 Task 对象，注册它激发的事件，并且实现这些事件的处理。因为事件是在 UI 线程上激发的，所以您根本不必担心代码中的线程处理问题。

下面的示例展示了如何创建 Task 对象。现假设UI 有两个按钮，一个用于启动运算，一个用于停止运算，还有一个进度栏显示当前的计算进度。

// 创建任务管理对象
_Task = new Task(); 
// 挂接任务管理对象工作状态变化事件
_Task.TaskStatusChanged += new TaskEventHandler( OnTaskStatusChanged ); 
// 挂接任务管理对象工作进度变化事件
_Task.TaskProgressChanged += new TaskEventHandler( OnTaskProgressChanged ); 

(1)
用于计算状态和计算进度事件的事件处理程序相应地更新 UI，例如通过更新状态栏控件。 

private void OnTaskProgressChanged( object sender,TaskEventArgs e ) 
{ 
    _progressBar.Value = e.Progress; 
} 
(2)
下面的代码展示的 TaskStatusChanged 事件处理程序更新进度栏的值以反映当前的计算进度。假定进度栏的最小值和最大值已经初始化。

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

在这个示例中，TaskStatusChanged 事件处理程序根据计算状态启用和禁用启动和停止按钮。这可以防止用户尝试启动一个已经在进行的计算，并且向用户提供有关计算状态的反馈。

通过使用 Task 对象中的公共方法，UI 为每个按钮单击实现了窗体事件处理程序，以便启动和停止计算。例如，启动按钮事件处理程序调用 StartTask 方法，如下所示。

private void startButton_Click( object sender, System.EventArgs e ) 
{ 
    _Task.StartTask( new object[] {} ); 
} 

类似地，停止计算按钮通过调用 StopTask 方法来停止计算，如下所示。

private void stopButton_Click( object sender, System.EventArgs e ) 
{ 
    _Task.StopTask(); 
} 

二.可能在非UI线程中使用Task类时
(1)和(2)应作如下改变

(1)
用于计算状态和计算进度事件的事件处理程序相应地更新 UI，例如通过更新状态栏控件。 

private void OnTaskProgressChanged( object sender,TaskEventArgs e ) 
{ 
	if (InvokeRequired )		//不在UI线程上,异步调用
	{
		TaskEventHandler TPChanged = new TaskEventHandler( OnTaskProgressChanged ); 
		this.BeginInvoke(TPChanged,new object[] {sender,e});
	}
	else						//更新
	{
		_progressBar.Value = e.Progress; 
	}
} 
(2)
下面的代码展示的 TaskStatusChanged 事件处理程序更新进度栏的值以反映当前的计算进度。假定进度栏的最小值和最大值已经初始化。

private void OnTaskStatusChanged( object sender, TaskEventArgs e ) 
{ 
	if (InvokeRequired )		//不在UI线程上,异步调用
	{
		TaskEventHandler TSChanged = new TaskEventHandler( OnTaskStatusChanged ); 
		this.BeginInvoke(TSChanged,new object[] {sender,e});
	}
	else						//更新
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
