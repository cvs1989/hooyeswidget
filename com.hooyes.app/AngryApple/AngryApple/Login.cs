using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace com.hooyes.app.AngryApple
{
    public partial class Login : Form
    {
        public Login()
        {
            InitializeComponent();
        }

        private void login_btn_Click(object sender, EventArgs e)
        {
            try
            {
                string key = textBox1.Text;
                if (string.IsNullOrEmpty(key))
                {
                    MessageBox.Show("请输入授权码");
                    return;
                }
                var r = I.V(key);
                if (r.Code == 0)
                {
                    Login.ActiveForm.Hide();
                    var f1 = new f1();
                    f1.key = textBox1.Text;
                    f1.Show();
                }
                else
                {
                    MessageBox.Show("授权码错误");
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
                log.Info("{0},{1}","login", ex.Message);
            }
        }


    }
}
