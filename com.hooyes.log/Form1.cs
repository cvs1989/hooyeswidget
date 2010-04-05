﻿using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using System.Net.NetworkInformation;
using com.hooyes.log.core;
using com.hooyes.widget;

namespace com.hooyes.log
{

    public partial class Form1 : Form
    {
        delegate bool SendMsg(string val);
        public Form1()
        {
            InitializeComponent();

        }

        private void Btn_Click(object sender, EventArgs e)
        {
            if (!string.IsNullOrEmpty(LogTextBox.Text))
            {
                string NetStatus = "";
                fn f = new fn();
                SendMsg s = new SendMsg(f.LogToTxt);
                PingReply reply = new Ping().Send("hooyeslog.appspot.com",3000);
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

                if (s(LogTextBox.Text))
                {
                    ShowMsg( NetStatus+ "|"+ LogTextBox.Text);
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