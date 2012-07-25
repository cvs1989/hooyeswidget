using System;
using System.Data;
using System.Threading;
using System.Windows.Forms;

namespace com.hooyes.app.AngryApple
{
    public partial class f1 : Form
    {
        
        public string key = string.Empty;
        private delegate void SetPos(int Value, int Maximum, bool Finish, string Message, DataTable dt);
        public f1()
        {
            InitializeComponent();
        }

        private void SetTextMessage(int Value, int Maximum, bool Finish, string Message, DataTable dt)
        {
            if (this.InvokeRequired)
            {
                SetPos setpos = new SetPos(SetTextMessage);
                this.Invoke(setpos, new object[] { Value, Maximum, Finish, Message, dt });
            }
            else
            {
                this.panel1.Show();
                this.EnabledBtn(false);
                this.label1.Text = string.Format("{0}/{1}", Value, Maximum);
                this.label2.Text = Message;
                this.progressBar1.Value = Value;
                this.progressBar1.Maximum = Maximum;
                if (Finish)
                {
                    dataGridView1.DataSource = dt;
                    this.panel1.Hide();
                    this.EnabledBtn(true);
                }
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            string resultFile = "";
            var o = new OpenFileDialog();
            o.Filter = "All files (*.*)|*.*|Excel files (*.xls)|*.xls";
            o.FilterIndex = 2;
            o.RestoreDirectory = true;
            if (o.ShowDialog() == DialogResult.OK)
            {
                resultFile = o.FileName;
                textBox1.Text = resultFile;
                EnabledBtn(true);
            }
        }

        private void f1_Load(object sender, EventArgs e)
        {
            dataGridView1.Dock = DockStyle.Right;
            panel1.Hide();
            EnabledBtn(false);
        }

        private void f1_FormClosing(object sender, FormClosingEventArgs e)
        {
            if (MessageBox.Show("确定退出程序吗？", "提示", MessageBoxButtons.YesNo) == DialogResult.No)
            {
                e.Cancel = true;
            }
        }

        private void f1_FormClosed(object sender, FormClosedEventArgs e)
        {
            Application.Exit();
        }

        private void button3_Click(object sender, EventArgs e)
        {
            var d = E.ExcuteDataset(textBox1.Text, "select * from [sheet1$]");

            dataGridView1.DataSource = d.Tables[0];

        }

        private void button2_Click(object sender, EventArgs e)
        {
            StartProcess();
        }
        private void button4_Click(object sender, EventArgs e)
        {
            StartProcess();
        }

        private void EnabledBtn(bool b)
        {
            button2.Enabled = b;
            button3.Enabled = b;
            button4.Enabled = b;
        }

        private void StartProcess()
        {
            dataGridView1.DataSource = null;
            string t = textBox1.Text;
            parm p = new parm();
            p.k = key;
            p.t = t;
            Thread th2 = new Thread(new ParameterizedThreadStart(ProcessData));
            th2.Start(p);
        }

        private void ProcessData(object data)
        {

            parm d = data as parm;
            var SN = U.CreateSN();
            var dt = D.B(d.t, SN, d.k, SetTextMessage);
        }

    }
    public class parm
    {
        public string t { get; set; }
        public string k { get; set; }
    }
}
