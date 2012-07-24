using System;
using System.ServiceModel;
using System.ServiceModel.Channels;
using com.hooyes.app.AngryApple.SR;
namespace com.hooyes.app.AngryApple
{
    public class I
    {
        public static string url = "http://dev.hooyes.com/henanSvc/Service1.svc";
        public static R S(M1 m,string k)
        {
            Binding bind;
            EndpointAddress remoteAddress;
            BE(url, out bind, out remoteAddress);
            var r = new R();
            var c = new Service1Client(bind, remoteAddress);
            var r1 = c.I(m, k);
            r.Code = r1.Code;
            r.Value = r1.Value;
            r.SN = r1.SN;
            r.Message = r1.Message;
            c.Close();
            return r;
        }
        public static R V(string k)
        {
            Binding bind;
            EndpointAddress remoteAddress;
            BE(url, out bind, out remoteAddress);
            
            var r = new R();
            var c = new Service1Client(bind, remoteAddress);
            var r1 = c.V(k);
            r.Code = r1.Code;
            r.Value = r1.Value;
            r.SN = r1.SN;
            r.Message = r1.Message;
            c.Close();
            return r;
        }
        private static void BE(string uri, out Binding bind, out EndpointAddress address)
        {
            if (string.IsNullOrWhiteSpace(uri))
            {
                bind = null;
                address = null;
                return;
            }

            string header = uri.Trim().ToLower();
            if (header.StartsWith("net.tcp"))
            {
                bind = new NetTcpBinding()
                {
                    MaxBufferSize = 65536 * 1024,
                    MaxReceivedMessageSize = 65536 * 1024
                };
                // ((NetTcpBinding)bind).Security.Mode = SecurityMode.None;
                ((NetTcpBinding)bind).MaxConnections = 1000;
                ((NetTcpBinding)bind).ReaderQuotas.MaxStringContentLength = 8192 * 1024;
            }
            else if (header.StartsWith("http"))
            {
                bind = new BasicHttpBinding()
                {
                    MaxBufferSize = 65536 * 1024,
                    MaxReceivedMessageSize = 65536 * 1024
                };

                ((BasicHttpBinding)bind).ReaderQuotas.MaxStringContentLength = 8192 * 1024;
            }
            else if (header.StartsWith("net.pipe"))
            {
                bind = new NetNamedPipeBinding()
                {
                    MaxBufferSize = 65536 * 1024,
                    MaxReceivedMessageSize = 65536 * 1024
                };

                ((NetNamedPipeBinding)bind).ReaderQuotas.MaxStringContentLength = 8192 * 1024;
            }
            else
                throw new ArgumentException("Wrong uri format which is " + uri);

            address = new EndpointAddress(uri);
        }


    }
}
