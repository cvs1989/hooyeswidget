namespace com.hooyes.widget
{
    using System;
    using System.IO;
    using System.Net;
    using System.Text;

    public class http
    {
        private const string sContentType = "application/x-www-form-urlencoded";
        private const string sRequestEncoding = "UTF-8";
        private const string sResponseEncoding = "UTF-8";
        private const string sUserAgent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";

        public static string PostDataToUrl(string data, string url)
        {
            return PostDataToUrl(Encoding.GetEncoding("UTF-8").GetBytes(data), url);
        }

        public static string PostDataToUrl(byte[] data, string url)
        {
            Stream responseStream;
            HttpWebRequest request2 = WebRequest.Create(url) as HttpWebRequest;
            if (request2 == null)
            {
                throw new ApplicationException(string.Format("Invalid url string: {0}", url));
            }
            request2.UserAgent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727)";
            request2.ContentType = "application/x-www-form-urlencoded";
            request2.Method = "POST";
            request2.ContentLength = data.Length;
            Stream requestStream = request2.GetRequestStream();
            requestStream.Write(data, 0, data.Length);
            requestStream.Close();
            try
            {
                responseStream = request2.GetResponse().GetResponseStream();
            }
            catch (Exception exception)
            {
                //Console.WriteLine(string.Format("POST操作发生异常：{0}", exception.Message));
                throw exception;
            }
            string str = string.Empty;
            using (StreamReader reader = new StreamReader(responseStream, Encoding.GetEncoding("UTF-8")))
            {
                str = reader.ReadToEnd();
            }
            responseStream.Close();
            return str;
        }
    }
}

