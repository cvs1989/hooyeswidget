using System;
using System.Collections.Generic;
using System.Text;
using System.ComponentModel;

using Thrift.Transport;
using Thrift.Protocol;
using System.IO;

namespace Hdfs.Library
{
   public class HdfsClient
    {
       public string HostServer { get; set; }
       public int Port { get; set; }

       public HdfsClient(string host, int port)
       {
           HostServer = host;
           Port = port;
       }

       //获取连接
       public ThriftHadoopFileSystem.Client Connect(out TBufferedTransport tsport)
       {
           if (HostServer == null)
           {
               throw new ArgumentNullException("HostServer");
           }

           if (Port == 0)
           {
               throw new ArgumentNullException("Port");
           }

           TSocket hadoop_socket = new TSocket(HostServer, Port);
           
           //hadoop_socket.Timeout = 10000;// Ten seconds

           tsport = new TBufferedTransport(hadoop_socket);
           
           TBinaryProtocol hadoop_protocol = new TBinaryProtocol(tsport, false, false);

           ThriftHadoopFileSystem.Client client = new ThriftHadoopFileSystem.Client(hadoop_protocol);
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

       //列表目录
       public List<FileStatus> GetFlolderList(string path)
       {
           TBufferedTransport tsport = null;
           ThriftHadoopFileSystem.Client client = Connect(out tsport);
           List<FileStatus> result = null; //new List<FileStatus>();
           //客户端可连接 且目录存在
           if (client != null && client.exists(new Pathname { pathname = path }))
           {
               result = client.listStatus(new Pathname() { pathname = path });
               tsport.Close();
           }
           return result;
       }

       //文件状态
       public FileStatus GetFileStatus(string path)
       {
           TBufferedTransport tsport = null;
           ThriftHadoopFileSystem.Client client = Connect(out tsport);
           FileStatus result = null; //new List<FileStatus>();
           //客户端可连接 且目录存在
           if (client != null&& client.exists(new Pathname { pathname = path }))
           {
               result = client.stat(new Pathname() { pathname = path });
               tsport.Close();
           }
           return result;
       }

       //新建文件夹
       public bool MakeDir(string path)
       {
           TBufferedTransport tsport = null;
           ThriftHadoopFileSystem.Client client = Connect(out tsport);
           bool result = false;
           if (client != null)
           {
               Pathname pn = new Pathname() { pathname = path };
               if (!client.exists(pn))//如果不存在才执行
                   result = client.mkdirs(pn);
               tsport.Close();
           }
           return result;
       }

       //重命名文件或文件夹
       public bool ReName(string oldPath,string newPath)
       {
           TBufferedTransport tsport = null;
           ThriftHadoopFileSystem.Client client = Connect(out tsport);
           bool result = false;
           if (client != null)
           {
               Pathname pn=new Pathname() { pathname = oldPath };
               if (client.exists(pn))//如果存在才执行
                   result = client.rename(pn, new Pathname() { pathname = newPath });
              
               tsport.Close();
           }
           return result;
       }

       //下载
       public bool Open(ThriftHadoopFileSystem.Client client,string path,string savePath,long fileLength)
       {
           bool result = false;
           if (client != null)
           {
               ThriftHandle th = client.open(new Pathname() { pathname = path });
               
               // 创建文件流
               FileStream fs = new FileStream(savePath, FileMode.Create, FileAccess.Write);
               long totalBytes = 0;
               int readLength=1024*1024;
               try
               {
                   UTF8Encoding utf8 = new UTF8Encoding(false,true);

                   while (true)
                   {
                       int needRead = readLength;
                       if (fileLength - totalBytes < readLength)
                       {
                           needRead = (int)(fileLength - totalBytes);
                       }
                       if (needRead <= 0)
                           break;

                       byte[] fileBuffer = client.read(th, totalBytes, readLength);


                       byte[] myfileBuffer =  Encoding.Convert(utf8, Encoding.GetEncoding("iso-8859-1"), fileBuffer);
                       
                       
                       totalBytes += readLength;

                       fs.Write(myfileBuffer, 0, myfileBuffer.Length);
                       
                   }
                   result = true;
                   
                  
               }
               catch (Exception ex)
               {
                   throw ex;
               }
               finally
               {
                   fs.Dispose();
                   if (client != null)
                       client.close(th);
               }
           }
           return result;
       }


       //上传文件
       public bool Create(ThriftHadoopFileSystem.Client client, string localPath, string path)
       {
           bool result = false;
           if (client != null)
           {
               ThriftHandle th = null;
               FileStream fs = null;
               try
               {
                   //创建一个文件
                   //th = client.createFile(new Pathname() { pathname = path }, 1, true, 1024, ConfigHelper.HDFSREPLICATION, 1024*1024*64);
                   th = client.createFile(new Pathname() { pathname = path }, 1, true, 1024, 1, 1024 * 1024 * 64);
                   
                   UTF8Encoding utf8 = new UTF8Encoding(false,true);

                   fs = new FileStream(localPath, FileMode.Open, FileAccess.Read);

                   byte[] fileBuffer = new byte[1024 * 1024];	// 每次传1MB
                   int bytesRead;
                   while ((bytesRead = fs.Read(fileBuffer, 0, fileBuffer.Length)) > 0)
                   {
                       byte[] realBuffer = new byte[bytesRead];
                       Array.Copy(fileBuffer, realBuffer, bytesRead);
                       //将utf8转为可存储编码
                       byte[] buf = Encoding.Convert(Encoding.GetEncoding("iso-8859-1"), utf8, realBuffer);
                       //发送
                       client.write(th,buf);
                       //清仓缓存
                       Array.Clear(fileBuffer, 0, fileBuffer.Length);
                   }
                   result = true;
               }
               catch (Exception ex)
               {
                   throw ex;
               }
               finally
               {
                   if (th != null)
                       client.close(th);
                   if (fs != null)
                       fs.Close();
               }
           }

           return result;
       }


       //上传文件
       public bool Create(ThriftHadoopFileSystem.Client client, byte[] data, string path)
       {
           bool result = false;
           if (client != null)
           {
               ThriftHandle th = null;
               try
               {
                   //创建一个文件
                   //th = client.createFile(new Pathname() { pathname = path }, 1, true, 1024, ConfigHelper.HDFSREPLICATION, 1024*1024*64);
                   th = client.createFile(new Pathname() { pathname = path }, 1, true, 1024, 1, 1024 * 1024 * 64);

                   UTF8Encoding utf8 = new UTF8Encoding(false, true);

                   byte[] buf = Encoding.Convert(Encoding.GetEncoding("iso-8859-1"), utf8, data);
                   //发送
                   client.write(th, buf);
                   result = true;
               }
               catch (Exception ex)
               {
                   throw ex;
               }
               finally
               {
                   if (th != null)
                       client.close(th);
               }
           }

           return result;
       }

       
       /// <summary>
       /// 删除文件或文件夹
       /// </summary>
       /// <param name="path"></param>
       /// <param name="recursive">是否删除子文件夹</param>
       /// <returns></returns>
       public bool Delete(string path,bool recursive)
       {
           TBufferedTransport tsport = null;
           ThriftHadoopFileSystem.Client client = Connect(out tsport);
           bool result = false;
           if (client != null)
           {
               Pathname pn = new Pathname() { pathname = path };
               if (client.exists(pn))//如果不存在才执行
                   result = client.rm(pn, recursive);
               tsport.Close();
               
           }
           return result;
       }

       //剪切
       public List<string> Move(string[] sourcePath, string dectPath)
       {
           TBufferedTransport tsport = null;
           ThriftHadoopFileSystem.Client client = Connect(out tsport);
           List<string> result = new List<string>();
           if (client != null)
           {
               foreach (string itemSource in sourcePath)
               {
                   Pathname pn = new Pathname() { pathname = itemSource };
                  string fileName= itemSource.Substring(itemSource.LastIndexOf('/')+1);
                  if (client.exists(pn))//如果存在才执行
                  { 
                    bool thResult=  client.rename(pn, new Pathname() { pathname = dectPath + "/" + fileName });
                    if (!thResult)
                    {
                        result.Add(fileName);
                    }
                  }
               }
               tsport.Close();
           }
           return result;
       }

    }
}
