using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using com.hooyes.log.core;

namespace com.hooyes.log
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void Btn_Click(object sender, EventArgs e)
        {
            if (!string.IsNullOrEmpty(LogTextBox.Text))
            {
                fn f = new fn();
                if (f.LogToTxt(LogTextBox.Text))
                {
                    ShowMsg(LogTextBox.Text);
                    LogTextBox.Text = "";
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




    }
}
