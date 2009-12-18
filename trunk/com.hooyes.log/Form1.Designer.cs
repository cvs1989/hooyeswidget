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
            this.SuspendLayout();
            // 
            // LogTextBox
            // 
            this.LogTextBox.Location = new System.Drawing.Point(12, 12);
            this.LogTextBox.Multiline = true;
            this.LogTextBox.Name = "LogTextBox";
            this.LogTextBox.Size = new System.Drawing.Size(296, 86);
            this.LogTextBox.TabIndex = 0;
            // 
            // Btn
            // 
            this.Btn.Location = new System.Drawing.Point(326, 12);
            this.Btn.Name = "Btn";
            this.Btn.Size = new System.Drawing.Size(75, 86);
            this.Btn.TabIndex = 1;
            this.Btn.Text = "Submit";
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
            this.LabelMsgShow.Location = new System.Drawing.Point(13, 105);
            this.LabelMsgShow.Name = "LabelMsgShow";
            this.LabelMsgShow.Size = new System.Drawing.Size(10, 13);
            this.LabelMsgShow.TabIndex = 2;
            this.LabelMsgShow.Text = ".";
            // 
            // Form1
            // 
            this.AcceptButton = this.Btn;
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(443, 124);
            this.Controls.Add(this.LabelMsgShow);
            this.Controls.Add(this.Btn);
            this.Controls.Add(this.LogTextBox);
            this.Name = "Form1";
            this.ShowInTaskbar = false;
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "Log My Work";
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
    }
}

