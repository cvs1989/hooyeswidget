using System;
using System.IO;
using System.Net;
using System.Text;

namespace com.hooyes.lms
{
    public class http
    {
        private const string sContentType = "application/x-www-form-urlencoded";
        private static NLog.Logger log = NLog.LogManager.GetCurrentClassLogger();
        public static string Send(string data, string url)
        {
            log.Info("{0},{1}", data, url);
            return Send(Encoding.GetEncoding("UTF-8").GetBytes(data), url);
        }

        public static string Send(byte[] data, string url)
        {
            Stream responseStream;
            HttpWebRequest request = WebRequest.Create(url) as HttpWebRequest;
            if (request == null)
            {
                log.Fatal(string.Format("Invalid url string: {0}", url));
                throw new ApplicationException(string.Format("Invalid url string: {0}", url));
            }
            request.ContentType = sContentType;
            request.Method = "POST";
            request.ContentLength = data.Length;
            Stream requestStream = request.GetRequestStream();
            requestStream.Write(data, 0, data.Length);
            requestStream.Close();
            try
            {
                responseStream = request.GetResponse().GetResponseStream();
            }
            catch (Exception exception)
            {
                log.Fatal("{0},{1}",exception.Message,exception.StackTrace);
                throw exception;
            }
            string str = string.Empty;
            using (StreamReader reader = new StreamReader(responseStream, Encoding.GetEncoding("UTF-8")))
            {
                str = reader.ReadToEnd();
            }
            responseStream.Close();
            log.Info("{0}", str);
            return str;
        }
    }
}

