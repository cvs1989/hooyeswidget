using System;
using System.Threading;
using Net66.AsynchThread;

namespace Demo
{
	/// <summary>
	/// newasynchui ��ժҪ˵����
	/// </summary>
	public class newasynchui:Task
	{
		public newasynchui()
		{
			//
			// TODO: �ڴ˴���ӹ��캯���߼�
			//
		}		
		/// <summary>
		/// ���ڴ����쳣
		/// </summary>
		public int errorkey = 1;
		override public object Work(params object[] args)
		{
			base.Work(args);
			for(int i =0;i<100;i++)
			{
				if (_taskState == TaskStatus.CancelPending)
				{
					break;
				}
				if(errorkey==0)
				{
					errorkey = i/errorkey;
				}
				Thread thread = Thread.CurrentThread;
				if (thread != null) 
					Console.WriteLine("�̺߳�:[{0}],�߳�����:[{1}],�߳�״̬:[{2}],��ǰʱ��:[{3}],ѭ������:[{4}].",thread.Name,thread.GetHashCode(),"",DateTime.Now.ToLongTimeString(),i.ToString());
				else
					Console.WriteLine("�̺߳�:[{0}],�߳�����:[{1}],�߳�״̬:[{2}],��ǰʱ��:[{3}],ѭ������:[{4}].","","","",DateTime.Now.ToLongTimeString(),i.ToString());
				Thread.Sleep(100*1);
				this.FireProgressChangedEvent(i,i);
			}
			return 100;
		}
		public object Work2(params object[] args)
		{
			base.Work(args);
			for(int i =0;i<100;i++)
			{
				if (_taskState == TaskStatus.CancelPending)
				{
					break;
				}
				if(errorkey==0)
				{
					errorkey = i/errorkey;
				}
				Thread thread = Thread.CurrentThread;
				if (thread != null) 
				{	Console.WriteLine("�̺߳�:[{0}],�߳�����:[{1}],�߳�״̬:[{2}],��ǰʱ��:[{3}],ѭ������:[{4}].",thread.Name,thread.GetHashCode(),"",DateTime.Now.ToLongTimeString(),i.ToString());}
				else
				{	Console.WriteLine("�̺߳�:[{0}],�߳�����:[{1}],�߳�״̬:[{2}],��ǰʱ��:[{3}],ѭ������:[{4}].","","","",DateTime.Now.ToLongTimeString(),i.ToString());}
				Thread.Sleep(100*1);
				this.FireProgressChangedEvent(i,i);
			}
			return 100;
		}
	}
}
