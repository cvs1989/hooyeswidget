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

        //�����̼߳�ͨѶ
        private int myhWnd = 0;

        //�߳�ͨѶ���ݶ���
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
            /*http����combobxo��ʼ��*/
            comboMethod.Items.Add("GET");
            comboMethod.Items.Add("POST");
            comboMethod.SelectedIndex = 0;

            /*����ͷ��������ؼ���ʼ��*/
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

            /*�ϴ�ͼƬ��������ؼ���ʼ��*/
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

            /*����formĬ�ϰ�ť*/
            buttonSend.NotifyDefault(true);
        }

        private void buttonSend_Click(object sender, EventArgs e)
        {
            textOutput.Text = "";
            if (string.IsNullOrEmpty(textURL.Text))
            {
                MessageBox.Show("URL����Ϊ��");
                return;
            }

            List<Parameter> parameters = new List<Parameter>();
            OauthKey oauthKey = new OauthKey();
            oauthKey.customKey = appKey;
            oauthKey.customSecrect = appSecret;
            oauthKey.tokenKey = accessKey;
            oauthKey.tokenSecrect = accessSecret;

            bool getMethod = comboMethod.SelectedIndex == 0 ? true : false;
            
            /*http���������name������*/
            List<string> arryName = new List<string>();
            /*http���������value������*/
            List<string> arryValue = new List<string>();

            listViewHeader.GetColumnItem(1, arryName);
            listViewHeader.GetColumnItem(2, arryValue);

            /*����listview�õ���value����utf8����,�������������б�*/
            for (int i = 0; i < arryName.Count; i++)
            {
                UTF8Encoding utf8 = new UTF8Encoding();
                Byte[] encodedBytes = utf8.GetBytes(arryValue[i]);
                string  content = utf8.GetString(encodedBytes);
                parameters.Add(new Parameter(arryName[i], content));
            }

            /*���http������POST��������ϴ�ͼƬ��Ϣ*/
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
                textOutput.Text = "������...";
            }
            else
            {
                textOutput.Text = "����ʧ��...";
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
        int hWnd, //Ŀ�괰�ڵ�handle
        int Msg, // ��Ϣ
        int wParam, // ��һ����Ϣ����
        int lParam // �ڶ�����Ϣ����
        );
        const int WM_HTTPNOTIFY = 8000;

        //�ú��������첽http�ĳ�������http�̵߳��ã�֪ͨ���߳���ʾhttp���
        protected void RequestCallback(int key, string content)
        {
            //ת���̵߳���
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

                    //������ȡ�������棬�Լ��ټ�����ʱ��
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