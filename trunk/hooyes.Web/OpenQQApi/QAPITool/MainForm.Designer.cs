namespace QAPITool
{
    partial class MainForm
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
            this.textURL = new System.Windows.Forms.TextBox();
            this.groupBox1 = new System.Windows.Forms.GroupBox();
            this.comboMethod = new System.Windows.Forms.ComboBox();
            this.buttonSend = new System.Windows.Forms.Button();
            this.groupBox2 = new System.Windows.Forms.GroupBox();
            this.button2 = new System.Windows.Forms.Button();
            this.button1 = new System.Windows.Forms.Button();
            this.groupBox3 = new System.Windows.Forms.GroupBox();
            this.textOutput = new System.Windows.Forms.TextBox();
            this.groupBox4 = new System.Windows.Forms.GroupBox();
            this.buttonPicDel = new System.Windows.Forms.Button();
            this.buttonPicAdd = new System.Windows.Forms.Button();
            this.listViewPic = new QAPITool.ListViewEx();
            this.listViewHeader = new QAPITool.ListViewEx();
            this.groupBox1.SuspendLayout();
            this.groupBox2.SuspendLayout();
            this.groupBox3.SuspendLayout();
            this.groupBox4.SuspendLayout();
            this.SuspendLayout();
            // 
            // textURL
            // 
            this.textURL.Location = new System.Drawing.Point(9, 17);
            this.textURL.Name = "textURL";
            this.textURL.Size = new System.Drawing.Size(305, 21);
            this.textURL.TabIndex = 0;
            // 
            // groupBox1
            // 
            this.groupBox1.Controls.Add(this.textURL);
            this.groupBox1.Location = new System.Drawing.Point(12, 5);
            this.groupBox1.Name = "groupBox1";
            this.groupBox1.Size = new System.Drawing.Size(321, 49);
            this.groupBox1.TabIndex = 1;
            this.groupBox1.TabStop = false;
            this.groupBox1.Text = "URL";
            // 
            // comboMethod
            // 
            this.comboMethod.FormattingEnabled = true;
            this.comboMethod.Location = new System.Drawing.Point(19, 64);
            this.comboMethod.Name = "comboMethod";
            this.comboMethod.Size = new System.Drawing.Size(68, 20);
            this.comboMethod.TabIndex = 2;
            this.comboMethod.SelectedIndexChanged += new System.EventHandler(this.OnComboSelChanged);
            // 
            // buttonSend
            // 
            this.buttonSend.Location = new System.Drawing.Point(251, 64);
            this.buttonSend.Name = "buttonSend";
            this.buttonSend.Size = new System.Drawing.Size(75, 23);
            this.buttonSend.TabIndex = 3;
            this.buttonSend.Text = "发送";
            this.buttonSend.UseVisualStyleBackColor = true;
            this.buttonSend.Click += new System.EventHandler(this.buttonSend_Click);
            // 
            // groupBox2
            // 
            this.groupBox2.Controls.Add(this.listViewHeader);
            this.groupBox2.Controls.Add(this.button2);
            this.groupBox2.Controls.Add(this.button1);
            this.groupBox2.Location = new System.Drawing.Point(12, 93);
            this.groupBox2.Name = "groupBox2";
            this.groupBox2.Size = new System.Drawing.Size(321, 212);
            this.groupBox2.TabIndex = 5;
            this.groupBox2.TabStop = false;
            this.groupBox2.Text = "请求头";
            // 
            // button2
            // 
            this.button2.Location = new System.Drawing.Point(67, 184);
            this.button2.Name = "button2";
            this.button2.Size = new System.Drawing.Size(61, 23);
            this.button2.TabIndex = 6;
            this.button2.Text = "删除所选";
            this.button2.UseVisualStyleBackColor = true;
            this.button2.Click += new System.EventHandler(this.OnHeaderDelSel);
            // 
            // button1
            // 
            this.button1.Location = new System.Drawing.Point(8, 184);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(55, 23);
            this.button1.TabIndex = 5;
            this.button1.Text = "添加";
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.OnHeaderAdd);
            // 
            // groupBox3
            // 
            this.groupBox3.Controls.Add(this.textOutput);
            this.groupBox3.Location = new System.Drawing.Point(340, 5);
            this.groupBox3.Name = "groupBox3";
            this.groupBox3.Size = new System.Drawing.Size(328, 480);
            this.groupBox3.TabIndex = 6;
            this.groupBox3.TabStop = false;
            this.groupBox3.Text = "结果";
            // 
            // textOutput
            // 
            this.textOutput.Location = new System.Drawing.Point(8, 17);
            this.textOutput.Multiline = true;
            this.textOutput.Name = "textOutput";
            this.textOutput.Size = new System.Drawing.Size(312, 455);
            this.textOutput.TabIndex = 0;
            // 
            // groupBox4
            // 
            this.groupBox4.Controls.Add(this.listViewPic);
            this.groupBox4.Controls.Add(this.buttonPicDel);
            this.groupBox4.Controls.Add(this.buttonPicAdd);
            this.groupBox4.Location = new System.Drawing.Point(12, 307);
            this.groupBox4.Name = "groupBox4";
            this.groupBox4.Size = new System.Drawing.Size(321, 181);
            this.groupBox4.TabIndex = 7;
            this.groupBox4.TabStop = false;
            this.groupBox4.Text = "上传图片路径";
            // 
            // buttonPicDel
            // 
            this.buttonPicDel.Location = new System.Drawing.Point(67, 147);
            this.buttonPicDel.Name = "buttonPicDel";
            this.buttonPicDel.Size = new System.Drawing.Size(61, 23);
            this.buttonPicDel.TabIndex = 6;
            this.buttonPicDel.Text = "删除所选";
            this.buttonPicDel.UseVisualStyleBackColor = true;
            this.buttonPicDel.Click += new System.EventHandler(this.OnPicDelSel);
            // 
            // buttonPicAdd
            // 
            this.buttonPicAdd.Location = new System.Drawing.Point(9, 147);
            this.buttonPicAdd.Name = "buttonPicAdd";
            this.buttonPicAdd.Size = new System.Drawing.Size(55, 23);
            this.buttonPicAdd.TabIndex = 5;
            this.buttonPicAdd.Text = "添加";
            this.buttonPicAdd.UseVisualStyleBackColor = true;
            this.buttonPicAdd.Click += new System.EventHandler(this.OnPicAdd);
            // 
            // listViewPic
            // 
            this.listViewPic.CheckBoxes = true;
            this.listViewPic.EditBgColor = System.Drawing.Color.LightBlue;
            this.listViewPic.EditFont = new System.Drawing.Font("SimSun", 9F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(134)));
            this.listViewPic.GridLines = true;
            this.listViewPic.Location = new System.Drawing.Point(9, 17);
            this.listViewPic.Name = "listViewPic";
            this.listViewPic.Size = new System.Drawing.Size(303, 124);
            this.listViewPic.TabIndex = 0;
            this.listViewPic.UseCompatibleStateImageBehavior = false;
            // 
            // listViewHeader
            // 
            this.listViewHeader.CheckBoxes = true;
            this.listViewHeader.EditBgColor = System.Drawing.Color.LightBlue;
            this.listViewHeader.EditFont = new System.Drawing.Font("SimSun", 9F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(134)));
            this.listViewHeader.GridLines = true;
            this.listViewHeader.LabelEdit = true;
            this.listViewHeader.Location = new System.Drawing.Point(9, 17);
            this.listViewHeader.Name = "listViewHeader";
            this.listViewHeader.Size = new System.Drawing.Size(303, 161);
            this.listViewHeader.TabIndex = 0;
            this.listViewHeader.UseCompatibleStateImageBehavior = false;
            // 
            // MainForm
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 12F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(680, 497);
            this.Controls.Add(this.groupBox4);
            this.Controls.Add(this.groupBox3);
            this.Controls.Add(this.groupBox2);
            this.Controls.Add(this.buttonSend);
            this.Controls.Add(this.comboMethod);
            this.Controls.Add(this.groupBox1);
            this.MaximizeBox = false;
            this.Name = "MainForm";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "接口测试工具";
            this.Load += new System.EventHandler(this.Form_Load);
            this.groupBox1.ResumeLayout(false);
            this.groupBox1.PerformLayout();
            this.groupBox2.ResumeLayout(false);
            this.groupBox3.ResumeLayout(false);
            this.groupBox3.PerformLayout();
            this.groupBox4.ResumeLayout(false);
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.TextBox textURL;
        private System.Windows.Forms.GroupBox groupBox1;
        private System.Windows.Forms.ComboBox comboMethod;
        private System.Windows.Forms.Button buttonSend;
        private System.Windows.Forms.GroupBox groupBox2;
        private System.Windows.Forms.Button button2;
        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.GroupBox groupBox3;
        private System.Windows.Forms.TextBox textOutput;
        private System.Windows.Forms.GroupBox groupBox4;
        private ListViewEx listViewHeader;
        private ListViewEx listViewPic;
        private System.Windows.Forms.Button buttonPicDel;
        private System.Windows.Forms.Button buttonPicAdd;
    }
}