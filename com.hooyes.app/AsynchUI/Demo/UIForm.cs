using System;
using System.ComponentModel;
using System.Drawing;
using System.Threading;
using System.Windows.Forms;
using Net66.AsynchThread;

namespace Demo
{
	/// <summary>
	/// UIForm 的摘要说明。
	/// </summary>
	public class UIForm : Form
	{
		newasynchui _Task;
		private Button button_start;
		private ProgressBar progressBar;
		private Label label_status;
		private Button button_stop;
		private Label label_progress;
		private Button button_abort;
		private Label label1;
		private Label label2;
		private Label label3;
		private Button button_fireerror;
		/// <summary>
		/// 必需的设计器变量。
		/// </summary>
		private Container components = null;

		public UIForm()
		{
			//
			// Windows 窗体设计器支持所必需的
			//
			InitializeComponent();

			//
			// TODO: 在 InitializeComponent 调用后添加任何构造函数代码
			//
			//创建任务管理对象
			_Task = new newasynchui(); 
			//挂接事件处理方法
			_Task.TaskStatusChanged += new TaskEventHandler( OnTaskStatusChanged ); 
			_Task.TaskProgressChanged += new TaskEventHandler( OnTaskProgressChanged1 ); 
			_Task.TaskProgressChanged += new TaskEventHandler( OnTaskProgressChanged2 ); 

		}

		/// <summary>
		/// 清理所有正在使用的资源。
		/// </summary>
		protected override void Dispose( bool disposing )
		{
			if( disposing )
			{
				if (components != null) 
				{
					components.Dispose();
				}
			}
			base.Dispose( disposing );
		}

		#region Windows 窗体设计器生成的代码
		/// <summary>
		/// 设计器支持所需的方法 - 不要使用代码编辑器修改
		/// 此方法的内容。
		/// </summary>
		private void InitializeComponent()
		{
			this.button_start = new System.Windows.Forms.Button();
			this.button_stop = new System.Windows.Forms.Button();
			this.progressBar = new System.Windows.Forms.ProgressBar();
			this.label_status = new System.Windows.Forms.Label();
			this.label_progress = new System.Windows.Forms.Label();
			this.button_abort = new System.Windows.Forms.Button();
			this.button_fireerror = new System.Windows.Forms.Button();
			this.label1 = new System.Windows.Forms.Label();
			this.label2 = new System.Windows.Forms.Label();
			this.label3 = new System.Windows.Forms.Label();
			this.SuspendLayout();
			// 
			// button_start
			// 
			this.button_start.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
				| System.Windows.Forms.AnchorStyles.Left)));
			this.button_start.Location = new System.Drawing.Point(32, 16);
			this.button_start.Name = "button_start";
			this.button_start.Size = new System.Drawing.Size(80, 32);
			this.button_start.TabIndex = 0;
			this.button_start.Text = "开始工作";
			this.button_start.Click += new System.EventHandler(this.button_start_Click);
			// 
			// button_stop
			// 
			this.button_stop.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
				| System.Windows.Forms.AnchorStyles.Right)));
			this.button_stop.Enabled = false;
			this.button_stop.Location = new System.Drawing.Point(503, 16);
			this.button_stop.Name = "button_stop";
			this.button_stop.Size = new System.Drawing.Size(80, 32);
			this.button_stop.TabIndex = 1;
			this.button_stop.Text = "正常取消";
			this.button_stop.Click += new System.EventHandler(this.button_stop_Click);
			// 
			// progressBar
			// 
			this.progressBar.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left) 
				| System.Windows.Forms.AnchorStyles.Right)));
			this.progressBar.Location = new System.Drawing.Point(80, 66);
			this.progressBar.Name = "progressBar";
			this.progressBar.Size = new System.Drawing.Size(504, 24);
			this.progressBar.TabIndex = 2;
			// 
			// label_status
			// 
			this.label_status.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
				| System.Windows.Forms.AnchorStyles.Left) 
				| System.Windows.Forms.AnchorStyles.Right)));
			this.label_status.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D;
			this.label_status.FlatStyle = System.Windows.Forms.FlatStyle.System;
			this.label_status.Location = new System.Drawing.Point(80, 136);
			this.label_status.Name = "label_status";
			this.label_status.Size = new System.Drawing.Size(504, 26);
			this.label_status.TabIndex = 3;
			this.label_status.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
			this.label_status.Click += new System.EventHandler(this.label_status_Click);
			// 
			// label_progress
			// 
			this.label_progress.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left) 
				| System.Windows.Forms.AnchorStyles.Right)));
			this.label_progress.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D;
			this.label_progress.FlatStyle = System.Windows.Forms.FlatStyle.System;
			this.label_progress.ForeColor = System.Drawing.SystemColors.ActiveCaption;
			this.label_progress.Location = new System.Drawing.Point(80, 104);
			this.label_progress.Name = "label_progress";
			this.label_progress.Size = new System.Drawing.Size(504, 17);
			this.label_progress.TabIndex = 4;
			this.label_progress.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
			// 
			// button_abort
			// 
			this.button_abort.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
				| System.Windows.Forms.AnchorStyles.Right)));
			this.button_abort.Enabled = false;
			this.button_abort.Location = new System.Drawing.Point(189, 16);
			this.button_abort.Name = "button_abort";
			this.button_abort.Size = new System.Drawing.Size(80, 32);
			this.button_abort.TabIndex = 1;
			this.button_abort.Text = "强行中止";
			this.button_abort.Click += new System.EventHandler(this.button_abort_Click);
			// 
			// button_fireerror
			// 
			this.button_fireerror.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
				| System.Windows.Forms.AnchorStyles.Right)));
			this.button_fireerror.Enabled = false;
			this.button_fireerror.Location = new System.Drawing.Point(346, 16);
			this.button_fireerror.Name = "button_fireerror";
			this.button_fireerror.Size = new System.Drawing.Size(80, 32);
			this.button_fireerror.TabIndex = 1;
			this.button_fireerror.Text = "触发异常";
			this.button_fireerror.Click += new System.EventHandler(this.button_fireerror_Click);
			// 
			// label1
			// 
			this.label1.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D;
			this.label1.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
			this.label1.Location = new System.Drawing.Point(32, 66);
			this.label1.Name = "label1";
			this.label1.Size = new System.Drawing.Size(48, 24);
			this.label1.TabIndex = 5;
			this.label1.Text = "进度条";
			this.label1.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
			// 
			// label2
			// 
			this.label2.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D;
			this.label2.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
			this.label2.Location = new System.Drawing.Point(32, 104);
			this.label2.Name = "label2";
			this.label2.Size = new System.Drawing.Size(48, 17);
			this.label2.TabIndex = 5;
			this.label2.Text = "完成率";
			this.label2.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
			// 
			// label3
			// 
			this.label3.BorderStyle = System.Windows.Forms.BorderStyle.Fixed3D;
			this.label3.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
			this.label3.Location = new System.Drawing.Point(32, 136);
			this.label3.Name = "label3";
			this.label3.Size = new System.Drawing.Size(48, 26);
			this.label3.TabIndex = 5;
			this.label3.Text = "状态串";
			this.label3.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
			// 
			// UIForm
			// 
			this.AutoScaleBaseSize = new System.Drawing.Size(6, 14);
			this.ClientSize = new System.Drawing.Size(616, 182);
			this.Controls.Add(this.label1);
			this.Controls.Add(this.label_progress);
			this.Controls.Add(this.label_status);
			this.Controls.Add(this.progressBar);
			this.Controls.Add(this.button_stop);
			this.Controls.Add(this.button_start);
			this.Controls.Add(this.button_abort);
			this.Controls.Add(this.button_fireerror);
			this.Controls.Add(this.label2);
			this.Controls.Add(this.label3);
			this.Name = "UIForm";
			this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
			this.Text = "后台线程管理类示例";
			this.ResumeLayout(false);

		}
		#endregion

		/// <summary>
		/// 应用程序的主入口点。
		/// </summary>
		[STAThread]
		static void Main() 
		{
			Application.Run(new UIForm());
		}

		private void button_start_Click(object sender, EventArgs e)
		{
//			_Task.StartTask(new object[]{});
//			_Task.errorkey = 1;
//			_Task.StartTask(new TaskDelegate(_Task.Work2),new object[]{});
			
			ThreadStart ts = new ThreadStart(start);
			Thread thread = new Thread(ts);
			thread.Start();

		}

		void start()
		{
			_Task.errorkey = 1;
			_Task.StartTask(new TaskDelegate(_Task.Work2),new object[]{});
		}
		private void button_abort_Click(object sender, EventArgs e)
		{
			_Task.AbortTask();
		}

		private void button_fireerror_Click(object sender, EventArgs e)
		{
			_Task.errorkey = 0;//将导致错误
		}

		private void button_stop_Click(object sender, EventArgs e)
		{
			_Task.StopTask();
		} 
		//在UI线程,负责更新进度条
		private void OnTaskProgressChanged1( object sender,TaskEventArgs e ) 
		{ 
			if (InvokeRequired )		//不在UI线程上,异步调用
			{
				TaskEventHandler TPChanged1 = new TaskEventHandler( OnTaskProgressChanged1 ); 
				this.BeginInvoke(TPChanged1,new object[] {sender,e});
				Console.WriteLine("InvokeRequired=true");
			}
			else
			{
				progressBar.Value = e.Progress;
			}
		} 
		//在UI线程,负责更新完成率
		private void OnTaskProgressChanged2( object sender,TaskEventArgs e ) 
		{ 
			label_progress.Text = string.Format("进度[{0}%],中间结果:{1}.",e.Progress.ToString(),e.Result!=null?e.Result.ToString():"null");
		} 
		//在UI线程,负责更新状态信息和按钮状态
		private void OnTaskStatusChanged( object sender, TaskEventArgs e ) 
		{ 
			string msg =string.Empty;
			switch ( e.Status ) 
			{ 
				case TaskStatus.Running: 
					button_start.Enabled = false;
					button_abort.Enabled = true;
					button_fireerror.Enabled = true;
					button_stop.Enabled = true; 
					break; 
				case TaskStatus.CancelPending: 
					button_start.Enabled = false; 
					button_abort.Enabled = false;
					button_fireerror.Enabled = false;
					button_stop.Enabled = false; 
					break; 
				case TaskStatus.AbortPending: 
					button_start.Enabled = false; 
					button_abort.Enabled = false;
					button_fireerror.Enabled = false;
					button_stop.Enabled = false; 
					break; 
				case TaskStatus.Stopped: 
					button_start.Enabled = true; 
					button_abort.Enabled = false;
					button_fireerror.Enabled = false;
					button_stop.Enabled = false;
					label_progress.Text =string.Empty;
					progressBar.Value =0;
					break; 
				case TaskStatus.ThrowErrorStoped:
					button_start.Enabled = true; 
					button_abort.Enabled = false;
					button_fireerror.Enabled = false;
					button_stop.Enabled = false;
					label_progress.Text =string.Empty;
					progressBar.Value =0;
					msg =_Task.Exception!=null?_Task.Exception.Message:"";
					break; 
				case TaskStatus.Aborted:
					button_start.Enabled = true; 
					button_abort.Enabled = false;
					button_fireerror.Enabled = false;
					button_stop.Enabled = false;
					label_progress.Text =string.Empty;
					progressBar.Value =0;
					msg ="被调用者强行中止.";
					break; 
			} 
			if (_Task.WordThread !=null) 
				label_status.Text = string.Format("任务状态:{0},线程名称:{1},线程编号:{2},线程状态:{3},运行结果:{4},其它信息:{5}.",e.Status,_Task.WordThread.Name,_Task.WordThread.GetHashCode(),_Task.WordThread.ThreadState,e.Result!=null ? e.Result.ToString():"null",msg); 
			else
				label_status.Text = string.Format("任务状态:{0},运行结果:{1},工作线程属性为NULL,其它信息:{2}.",e.Status,e.Result!=null ? e.Result.ToString():"null",msg); 

		}

		private void label_status_Click(object sender, EventArgs e)
		{
			MessageBox.Show(_Task.TaskState.ToString()+",CallThread:["+_Task.CallThread.GetHashCode().ToString()+"],WorkThread:["+_Task.WordThread.GetHashCode().ToString()+"]");
		}




	}
}
