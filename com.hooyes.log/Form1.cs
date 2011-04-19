using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.Net.NetworkInformation;
using com.hooyes.log.core;
using com.hooyes.widget;
using System.IO;

namespace com.hooyes.log
{

    public partial class Form1 : Form
    {
        string pic = string.Empty;
        delegate bool SendMsg(string val,string pic);
        public Form1()
        {
            InitializeComponent();

        }

        private void Btn_Click(object sender, EventArgs e)
        {
            
            //PickFile();
            if (!string.IsNullOrEmpty(LogTextBox.Text))
            {
                string NetStatus = "";
                fn f = new fn();
                SendMsg s = new SendMsg(f.Empty);
                if (checkBoxGoogle.Checked)
                {
                    PingReply reply = new Ping().Send("hooyeslog.appspot.com", 3000);
                    if (reply.Status == IPStatus.Success)
                    {
                        s += f.LogToGoogle;
                        NetStatus = "网络良好";
                    }
                    else
                    {
                        s -= f.LogToGoogle;
                        NetStatus = "网络不通";
                    }
                }
                if (checkLocal.Checked)
                {
                    s += f.LogToTxt;
                }
                if (checkBoxQQ.Checked)
                {
                    s += f.QQ;
                }
                if (checkBoxSina.Checked)
                {
                    s += f.Sina;
                }

                if (s(LogTextBox.Text,pic))
                {
                    ShowMsg("OK");
                    LogTextBox.Text = "";
                    pic = string.Empty;
                    linkPic.Text = "Picture";
                }
            }
        }
        private void ShowMsg(string msg)
        {
            LabelMsgShow.Text = msg;
        }

        private void notifyIcon1_MouseDoubleClick(object sender, MouseEventArgs e)
        {
            this.Visible = true;
            this.WindowState = FormWindowState.Normal;
            this.notifyIcon1.Visible = false; 
        }

        private void Form1_SizeChanged(object sender, EventArgs e)
        {
            if (this.WindowState == FormWindowState.Minimized)
            {
                this.Hide();
                this.notifyIcon1.Visible = true;
            }
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            this.Left = Screen.PrimaryScreen.WorkingArea.Width - this.Width;
            this.Top = Screen.PrimaryScreen.WorkingArea.Height - this.Height;
            this.notifyIcon1.Visible = false;

        }

        private string PickFile()
        {
            string resultFile = string.Empty;
            OpenFileDialog openFileDialog1 = new OpenFileDialog();
            //openFileDialog1.InitialDirectory = "C:";
            openFileDialog1.Filter = "All files (*.*)|*.*|jpg files (*.jpg)|*.jpg|gif files (*.gif)|*.gif";
            openFileDialog1.FilterIndex = 2;
            openFileDialog1.RestoreDirectory = true;
            if (openFileDialog1.ShowDialog() == DialogResult.OK)
                resultFile = openFileDialog1.FileName;

            return resultFile;

        }

        private void linkPic_LinkClicked(object sender, LinkLabelLinkClickedEventArgs e)
        {
           string temppic = PickFile();
           if (!string.IsNullOrEmpty(temppic))
            {
                pic = temppic;
                linkPic.Text = Path.GetFileName(pic);
            }
        }


    }
}
