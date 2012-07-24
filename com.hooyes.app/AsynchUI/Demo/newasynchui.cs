using System;
using System.Threading;
using Net66.AsynchThread;

namespace Demo
{
	/// <summary>
	/// newasynchui 的摘要说明。
	/// </summary>
	public class newasynchui:Task
	{
		public newasynchui()
		{
			//
			// TODO: 在此处添加构造函数逻辑
			//
		}		
		/// <summary>
		/// 用于触发异常
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
					Console.WriteLine("线程号:[{0}],线程名称:[{1}],线程状态:[{2}],当前时间:[{3}],循环次数:[{4}].",thread.Name,thread.GetHashCode(),"",DateTime.Now.ToLongTimeString(),i.ToString());
				else
					Console.WriteLine("线程号:[{0}],线程名称:[{1}],线程状态:[{2}],当前时间:[{3}],循环次数:[{4}].","","","",DateTime.Now.ToLongTimeString(),i.ToString());
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
				{	Console.WriteLine("线程号:[{0}],线程名称:[{1}],线程状态:[{2}],当前时间:[{3}],循环次数:[{4}].",thread.Name,thread.GetHashCode(),"",DateTime.Now.ToLongTimeString(),i.ToString());}
				else
				{	Console.WriteLine("线程号:[{0}],线程名称:[{1}],线程状态:[{2}],当前时间:[{3}],循环次数:[{4}].","","","",DateTime.Now.ToLongTimeString(),i.ToString());}
				Thread.Sleep(100*1);
				this.FireProgressChangedEvent(i,i);
			}
			return 100;
		}
	}
}
