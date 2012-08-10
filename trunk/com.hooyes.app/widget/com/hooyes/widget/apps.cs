namespace com.hooyes.widget
{
//using Newtonsoft.Json;
    using System;
    using System.Configuration;
    using System.Text.RegularExpressions;
    using System.Web;
    using System.Xml;

    public class apps
    {
        public static string langPairPicker(string rawWord)
        {
            string str = "zh-cn|en";
            string str2 = ConfigurationManager.AppSettings.Get("hooyesTranslateLangEN");
            string str3 = ConfigurationManager.AppSettings.Get("hooyesTranslateLangCN");
            string str4 = ConfigurationManager.AppSettings.Get("hooyesTranslateLangFlag");
            if (null == str3)
            {
                str2 = "chinese,english,french,japanese";
                str3 = "中,英,法,日";
                str4 = "zh-cn,en,fr,ja";
            }
            string[] strArray = str3.Split(new char[] { ',' });
            string[] strArray2 = str2.Split(new char[] { ',' });
            string[] strArray3 = str4.Split(new char[] { ',' });
            str = rawWord.ToLower();
            for (int i = 0; i < strArray.Length; i++)
            {
                str = str.Replace(strArray[i], strArray3[i]).Replace(strArray2[i], strArray3[i]);
            }
            return str.Replace("/", "|").Replace("(", "").Replace(")", "");
        }

        public static string prefix(string s)
        {
            return Regex.Match(s, @"\((.+?)\)").Groups[1].Value;
        }

        public static string randHello()
        {
            return randNews("http://news.baidu.com/n?cmd=1&class=civilnews&tn=rss&sub=0");
        }

        public static string randNews(string rssUrl)
        {
            XmlDocument document = new XmlDocument();
            document.Load(rssUrl);
            XmlNodeList list = document.SelectNodes("/rss/channel/item");
            int maxValue = list.Count - 1;
            int num2 = new Random().Next(0, maxValue);
            return list[num2].ChildNodes[0].InnerText;
        }

        //public static string translate(string q, string langpair, string hl)
        //{
        //    q = HttpUtility.UrlEncode(q);
        //    string url = "http://ajax.googleapis.com/ajax/services/language/translate";
        //    string format = "q={0}&v=1.0&context=bar&langpair={1}&hl={2}";
        //    string str3 = http.PostDataToUrl(string.Format(format, q, langpair, hl), url);
        //    com.hooyes.widget.translate translate = new com.hooyes.widget.translate();
        //    translate = JavaScriptConvert.DeserializeObject<com.hooyes.widget.translate>(str3);
        //    if (null != translate.responseData)
        //    {
        //        return translate.responseData.translatedText;
        //    }
        //    return "";
        //}
    }
}

