namespace com.hooyes.log
{
    partial class Form1
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(Form1));
            this.LogTextBox = new System.Windows.Forms.TextBox();
            this.Btn = new System.Windows.Forms.Button();
            this.notifyIcon1 = new System.Windows.Forms.NotifyIcon(this.components);
            this.LabelMsgShow = new System.Windows.Forms.Label();
            this.checkBoxQQ = new System.Windows.Forms.CheckBox();
            this.checkBoxSina = new System.Windows.Forms.CheckBox();
            this.checkBoxGoogle = new System.Windows.Forms.CheckBox();
            this.linkPic = new System.Windows.Forms.LinkLabel();
            this.checkLocal = new System.Windows.Forms.CheckBox();
            this.SuspendLayout();
            // 
            // LogTextBox
            // 
            this.LogTextBox.Location = new System.Drawing.Point(12, 11);
            this.LogTextBox.Multiline = true;
            this.LogTextBox.Name = "LogTextBox";
            this.LogTextBox.Size = new System.Drawing.Size(296, 80);
            this.LogTextBox.TabIndex = 0;
            // 
            // Btn
            // 
            this.Btn.Location = new System.Drawing.Point(326, 11);
            this.Btn.Name = "Btn";
            this.Btn.Size = new System.Drawing.Size(75, 79);
            this.Btn.TabIndex = 1;
            this.Btn.Text = "Tweet";
            this.Btn.UseVisualStyleBackColor = true;
            this.Btn.Click += new System.EventHandler(this.Btn_Click);
            // 
            // notifyIcon1
            // 
            this.notifyIcon1.Icon = ((System.Drawing.Icon)(resources.GetObject("notifyIcon1.Icon")));
            this.notifyIcon1.Text = "I am here ,Waiting for you Hooyes!";
            this.notifyIcon1.Visible = true;
            this.notifyIcon1.MouseDoubleClick += new System.Windows.Forms.MouseEventHandler(this.notifyIcon1_MouseDoubleClick);
            // 
            // LabelMsgShow
            // 
            this.LabelMsgShow.AutoSize = true;
            this.LabelMsgShow.Location = new System.Drawing.Point(13, 97);
            this.LabelMsgShow.Name = "LabelMsgShow";
            this.LabelMsgShow.Size = new System.Drawing.Size(11, 12);
            this.LabelMsgShow.TabIndex = 2;
            this.LabelMsgShow.Text = ".";
            // 
            // checkBoxQQ
            // 
            this.checkBoxQQ.AutoSize = true;
            this.checkBoxQQ.Location = new System.Drawing.Point(275, 96);
            this.checkBoxQQ.Name = "checkBoxQQ";
            this.checkBoxQQ.Size = new System.Drawing.Size(36, 16);
            this.checkBoxQQ.TabIndex = 3;
            this.checkBoxQQ.Text = "QQ";
            this.checkBoxQQ.UseVisualStyleBackColor = true;
            // 
            // checkBoxSina
            // 
            this.checkBoxSina.AutoSize = true;
            this.checkBoxSina.Location = new System.Drawing.Point(317, 96);
            this.checkBoxSina.Name = "checkBoxSina";
            this.checkBoxSina.Size = new System.Drawing.Size(48, 16);
            this.checkBoxSina.TabIndex = 4;
            this.checkBoxSina.Text = "Sina";
            this.checkBoxSina.UseVisualStyleBackColor = true;
            // 
            // checkBoxGoogle
            // 
            this.checkBoxGoogle.AutoSize = true;
            this.checkBoxGoogle.Location = new System.Drawing.Point(371, 97);
            this.checkBoxGoogle.Name = "checkBoxGoogle";
            this.checkBoxGoogle.Size = new System.Drawing.Size(60, 16);
            this.checkBoxGoogle.TabIndex = 5;
            this.checkBoxGoogle.Text = "Google";
            this.checkBoxGoogle.UseVisualStyleBackColor = true;
            // 
            // linkPic
            // 
            this.linkPic.AutoSize = true;
            this.linkPic.LinkColor = System.Drawing.Color.Maroon;
            this.linkPic.Location = new System.Drawing.Point(39, 98);
            this.linkPic.Name = "linkPic";
            this.linkPic.Size = new System.Drawing.Size(47, 12);
            this.linkPic.TabIndex = 6;
            this.linkPic.TabStop = true;
            this.linkPic.Text = "Picture";
            this.linkPic.LinkClicked += new System.Windows.Forms.LinkLabelLinkClickedEventHandler(this.linkPic_LinkClicked);
            // 
            // checkLocal
            // 
            this.checkLocal.AutoSize = true;
            this.checkLocal.Checked = true;
            this.checkLocal.CheckState = System.Windows.Forms.CheckState.Checked;
            this.checkLocal.Location = new System.Drawing.Point(215, 96);
            this.checkLocal.Name = "checkLocal";
            this.checkLocal.Size = new System.Drawing.Size(54, 16);
            this.checkLocal.TabIndex = 7;
            this.checkLocal.Text = "Local";
            this.checkLocal.UseVisualStyleBackColor = true;
            // 
            // Form1
            // 
            this.AcceptButton = this.Btn;
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 12F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(443, 114);
            this.Controls.Add(this.checkLocal);
            this.Controls.Add(this.linkPic);
            this.Controls.Add(this.checkBoxGoogle);
            this.Controls.Add(this.checkBoxSina);
            this.Controls.Add(this.checkBoxQQ);
            this.Controls.Add(this.LabelMsgShow);
            this.Controls.Add(this.Btn);
            this.Controls.Add(this.LogTextBox);
            this.Name = "Form1";
            this.ShowInTaskbar = false;
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "Microblog hooyes!";
            this.Load += new System.EventHandler(this.Form1_Load);
            this.SizeChanged += new System.EventHandler(this.Form1_SizeChanged);
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.TextBox LogTextBox;
        private System.Windows.Forms.Button Btn;
        private System.Windows.Forms.NotifyIcon notifyIcon1;
        private System.Windows.Forms.Label LabelMsgShow;
        private System.Windows.Forms.CheckBox checkBoxQQ;
        private System.Windows.Forms.CheckBox checkBoxSina;
        private System.Windows.Forms.CheckBox checkBoxGoogle;
        private System.Windows.Forms.LinkLabel linkPic;
        private System.Windows.Forms.CheckBox checkLocal;
    }
}

