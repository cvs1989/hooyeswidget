using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
using QWeiboSDK;

namespace QAPITool
{
    public partial class MainForm : Form
    {
        private string appKey = null;
        private string appSecret = null;
        private string accessKey = null;
        private string accessSecret = null;

        //用于线程间通讯
        private int myhWnd = 0;

        //线程通讯数据队列
        private Dictionary<int, string> dicData = new Dictionary<int, string>();

        public void SetAppKey(string appKey)
        {
            this.appKey = appKey;
        }
        public void SetAppSecret(string appSecret)
        {
            this.appSecret = appSecret;
        }
        public void SetAccessKey(string accessKey)
        {
            this.accessKey = accessKey;
        }
        public void SetAccessSecret(string accessSecret)
        {
            this.accessSecret = accessSecret;
        }

        public MainForm()
        {
            InitializeComponent();
            myhWnd = this.Handle.ToInt32();
        }

        private void Form_Load(object sender, EventArgs e)
        {
            /*http方法combobxo初始化*/
            comboMethod.Items.Add("GET");
            comboMethod.Items.Add("POST");
            comboMethod.SelectedIndex = 0;

            /*请求头参数输入控件初始化*/
            listViewHeader.GridLines = true;
            listViewHeader.Scrollable = true;
            listViewHeader.View = View.Details;
            listViewHeader.Visible = true;
            listViewHeader.Activation = ItemActivation.OneClick;

            listViewHeader.Columns.Add("", 20, HorizontalAlignment.Center);

            ColumnHeader header2 = new ColumnHeader();
            header2.Width = 120;
            header2.Text = "name";
            listViewHeader.Columns.Add(header2);

            ColumnHeader header3 = new ColumnHeader();
            header3.Width = 120;
            header3.Text = "value";
            listViewHeader.Columns.Add(header3);
            /******************************/

            /*上传图片参数输入控件初始化*/
            listViewPic.GridLines = true;
            listViewPic.Scrollable = true;
            listViewPic.View = View.Details;
            listViewPic.Visible = true;
            listViewPic.Enabled = false;

            listViewPic.Columns.Add("", 20, HorizontalAlignment.Center);
            ColumnHeader headerPic = new ColumnHeader();
            headerPic.Width = 250;
            headerPic.Text = "picture";
            listViewPic.Columns.Add(headerPic);
            /*******************************/

            buttonPicAdd.Enabled = false;
            buttonPicDel.Enabled = false;

            textOutput.ScrollBars = ScrollBars.Both;

            /*设置form默认按钮*/
            buttonSend.NotifyDefault(true);
        }

        private void buttonSend_Click(object sender, EventArgs e)
        {
            textOutput.Text = "";
            if (string.IsNullOrEmpty(textURL.Text))
            {
                MessageBox.Show("URL不能为空");
                return;
            }

            List<Parameter> parameters = new List<Parameter>();
            OauthKey oauthKey = new OauthKey();
            oauthKey.customKey = appKey;
            oauthKey.customSecrect = appSecret;
            oauthKey.tokenKey = accessKey;
            oauthKey.tokenSecrect = accessSecret;

            bool getMethod = comboMethod.SelectedIndex == 0 ? true : false;
            
            /*http请求参数的name域数组*/
            List<string> arryName = new List<string>();
            /*http请求参数的value域数组*/
            List<string> arryValue = new List<string>();

            listViewHeader.GetColumnItem(1, arryName);
            listViewHeader.GetColumnItem(2, arryValue);

            /*将从listview拿到的value进行utf8编码,后加入请求参数列表*/
            for (int i = 0; i < arryName.Count; i++)
            {
                UTF8Encoding utf8 = new UTF8Encoding();
                Byte[] encodedBytes = utf8.GetBytes(arryValue[i]);
                string  content = utf8.GetString(encodedBytes);
                parameters.Add(new Parameter(arryName[i], content));
            }

            /*如果http方法是POST，则加入上传图片信息*/
            List<Parameter> files = new List<Parameter>();
            if (getMethod == false)
            {
                List<string> arryPic = new List<string>();
                listViewPic.GetColumnItem(1, arryPic);
                for (int i = 0; i < arryPic.Count; i++)
                {
                    files.Add(new Parameter("pic", arryPic[i]));
                }
            }

            QWeiboRequest request = new QWeiboRequest();
            int nKey = 0;
            if (request.AsyncRequest(textURL.Text, getMethod == true? "GET":"POST", oauthKey, parameters, files, new AsyncRequestCallback(RequestCallback), out nKey))
            {
                textOutput.Text = "请求中...";
            }
            else
            {
                textOutput.Text = "请求失败...";
            }

        }

        private void OnComboSelChanged(object sender, EventArgs e)
        {
            int nIndex = comboMethod.SelectedIndex;

            if (nIndex == 0)//GET
            {
                listViewPic.Enabled = false;
                buttonPicAdd.Enabled = false;
                buttonPicDel.Enabled = false;
            }
            else if (nIndex == 1)//POST
            {
                listViewPic.Enabled = true;
                buttonPicAdd.Enabled = true;
                buttonPicDel.Enabled = true;
            }
        }


        [System.Runtime.InteropServices.DllImport("User32.dll", EntryPoint = "PostMessage")]
        private static extern int PostMessage(
        int hWnd, //目标窗口的handle
        int Msg, // 消息
        int wParam, // 第一个消息参数
        int lParam // 第二个消息参数
        );
        const int WM_HTTPNOTIFY = 8000;

        //该函数用于异步http的场景，由http线程调用，通知主线程显示http结果
        protected void RequestCallback(int key, string content)
        {
            //转换线程调用
            lock (dicData)
            {
                Encoding utf8 = Encoding.GetEncoding(65001);
                Encoding defaultChars = Encoding.Default;
                byte[] temp = utf8.GetBytes(content);
                byte[] temp1 = Encoding.Convert(utf8, defaultChars, temp);
                string result = defaultChars.GetString(temp1);
                dicData.Add(key, result);
            }

            PostMessage(myhWnd, WM_HTTPNOTIFY, 0, 0);
        }

        protected override void DefWndProc(ref System.Windows.Forms.Message m)
        {
            switch (m.Msg)
            {
                case WM_HTTPNOTIFY:

                    //把数据取出来缓存，以减少加锁的时间
                    Dictionary<int, string> dicText = new Dictionary<int, string>();
                    lock (dicData)
                    {
                        foreach (KeyValuePair<int, string> a in dicData)
                        {
                            dicText.Add(a.Key, a.Value);
                        }
                        dicData.Clear();
                    }
                    

                    foreach (KeyValuePair<int, string> a in dicText)
                    {
                        textOutput.Text = a.Value;
                    }

                    break;
                default:
                    base.DefWndProc(ref m);
                    break;
            }
        }

        private void OnHeaderAdd(object sender, EventArgs e)
        {
            string[] items = { "", "" };
            listViewHeader.AddItem(items);
        }

        private void OnHeaderDelSel(object sender, EventArgs e)
        {
            listViewHeader.DelSelItem();
        }

        private void OnPicAdd(object sender, EventArgs e)
        {
            string[] items = { "", "" };
            listViewPic.AddItem(items);
        }

        private void OnPicDelSel(object sender, EventArgs e)
        {
            listViewPic.DelSelItem();
        }
    }
}