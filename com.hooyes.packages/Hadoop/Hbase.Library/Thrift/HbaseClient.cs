using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Thrift.Transport;
using Thrift.Protocol;

namespace Hbase.Library
{
    public class HbaseClient
    {
        public string HostServer { get; set; }
        public int Port { get; set; }

        public HbaseClient(string host, int port)
        {
            HostServer = host;
            Port = port;
        }

        #region Connect 获取连接
        public Hbase.Client Connect(out TBufferedTransport tsport)
        {
            if (HostServer == null)
            {
                throw new ArgumentNullException("HostServer");
            }

            if (Port == 0)
            {
                throw new ArgumentNullException("Port");
            }

            TSocket hbase_socket = new TSocket(HostServer, Port);

            //hadoop_socket.Timeout = 10000;// Ten seconds

            tsport = new TBufferedTransport(hbase_socket);

            TBinaryProtocol hbase_protocol = new TBinaryProtocol(tsport, false, false);

            Hbase.Client client = new Hbase.Client(hbase_protocol);
            try
            {
                tsport.Open();
                return client;
            }
            catch (Exception ex)
            {
                //throw (new Exception("打开连接失败！", ex));
                tsport = null;
                return null;

            }
        }
        #endregion

        #region CreateTable 创建表
        public bool CreateTable(string tableName, List<ColumnDescriptor> columns)
        {
            bool result = false;
            TBufferedTransport tsport = null;
            Hbase.Client client = Connect(out tsport);
            if (client != null)
            {
                client.createTable(Unitl.StrToBytes(tableName), columns);
                tsport.Close();
                result = true;
            }
            return result;
        }
        #endregion

        #region UpdateRow 按行更新数据
        public bool UpdateRow(string tableName, string key, List<Mutation> mutations)
        {
            bool result = false;
            TBufferedTransport tsport = null;
            Hbase.Client client = Connect(out tsport);
            if (client != null)
            {
                client.mutateRow(Unitl.StrToBytes(tableName), Unitl.StrToBytes(key), mutations);
                tsport.Close();
                result = true;
            }
            return result;
        }
        #endregion

        #region UpdateRows 批量按行更新数据
        public bool UpdateRows(string tableName, List<BatchMutation> mutations)
        {
            bool result = false;
            TBufferedTransport tsport = null;
            Hbase.Client client = Connect(out tsport);
            if (client != null)
            {
                client.mutateRows(Unitl.StrToBytes(tableName), mutations);
                tsport.Close();
                result = true;
            }
            return result;
        }
        #endregion

        #region GetRow 按行获取数据
        public List<TRowResult> GetRow(string tableName, string key)
        {
            List<TRowResult> result = new List<TRowResult>();
            TBufferedTransport tsport = null;
            Hbase.Client client = Connect(out tsport);
            if (client != null)
            {
                result = client.getRow(Unitl.StrToBytes(tableName), Unitl.StrToBytes(key));
                tsport.Close();
            }
            return result;
        }
        #endregion

        #region Search 查询
        public List<TRowResult> Search(string tableName, TScan scan)
        {
            List<TRowResult> result = new List<TRowResult>();
            TBufferedTransport tsport = null;
            Hbase.Client client = Connect(out tsport);
            if (client != null)
            {
                int scanId = client.scannerOpenWithScan(Unitl.StrToBytes(tableName), scan);
                result = client.scannerGet(scanId);
                client.scannerClose(scanId);
                tsport.Close();
            }
            return result;
        }
        #endregion

    }
}
