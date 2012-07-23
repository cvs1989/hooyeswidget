using System;
using System.Windows.Forms;

namespace com.hooyes.app.AngryApple
{
    public partial class f1 : Form
    {
        Loading l;
        public string key = string.Empty;
        public f1()
        {
            InitializeComponent();
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
            //textBox1.Text = key;
            dataGridView1.Dock = DockStyle.Right;
            EnabledBtn(false);
            //l.Show();
            
           
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

            ProgressShow(true);
            EnabledBtn(false);
            var SN = U.CreateSN();
            var dt = D.B(textBox1.Text, SN, key);
            dataGridView1.DataSource = dt;
            EnabledBtn(true);
            ProgressShow(false);


        }
        private void button4_Click(object sender, EventArgs e)
        {
            ProgressShow(true);
            EnabledBtn(false);
            var SN = U.CreateSN();
            var dt = D.B(textBox1.Text, SN, key);
            dataGridView1.DataSource = dt;
            EnabledBtn(true);
            ProgressShow(false);
        }

        private void EnabledBtn(bool b)
        {
            button2.Enabled = b;
            button3.Enabled = b;
            button4.Enabled = b;
        }
        private void ProgressShow(bool b)
        {
            if (b)
            {
                if (l == null)
                {
                    l = new Loading();
                }
                l.TopLevel = true;
                l.Show();
            }
            else
            {
                l.Hide();
            }
        }

    }
}
