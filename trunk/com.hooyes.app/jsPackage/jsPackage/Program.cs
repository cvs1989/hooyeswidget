using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace jsPackage
{
    class Program
    {
        static string jsFilename = "98cb16d0-7165-40dc-8ade-663439dcbb63.js";
        static void Main(string[] args)
        {
            Pack(args);
        }
        static void Pack(string[] args)
        {
             string appPath = AppDomain.CurrentDomain.BaseDirectory;
             //string jsFilename = Guid.NewGuid().ToString() + ".js";
             string jsConfigFile = appPath + "Scripts/Signup.Config.js";
             string jsFilePath = appPath + "Scripts/Cache";
             string jsFileOutPath = appPath + "Scripts";
             DirectoryInfo dix = new DirectoryInfo(jsFileOutPath);
             if (!dix.Exists)
             {
                 dix.Create();
             }
            if (args.Length > 0)
            {
                StreamWriter sw = new StreamWriter(Path.Combine(jsFileOutPath, jsFilename), false);
                sw.WriteLine("// build date: {0}", DateTime.Now.ToString("yyyyMMdd HH:mm:ss"));
                foreach (string fileName in args)
                {
                    StreamReader sr = new StreamReader(Path.Combine(jsFilePath, fileName));

                    string temp = sr.ReadToEnd();

                    sw.WriteLine("// {0}", fileName);
                    sw.Write(temp);
                    sw.WriteLine(string.Empty);
                    sr.Close();
                }

                sw.Close();
                sw.Dispose();
                BuilHtml(args);
                Console.WriteLine("ok");
            }
            else
            {
                Console.WriteLine("no params,auto detect...");
                if (File.Exists(jsConfigFile))
                {
                    StreamReader srC = new StreamReader(jsConfigFile);
                    string tx = srC.ReadLine();

                    if (tx.StartsWith("//jsPackage.exe"))
                    {
                        tx = tx.Replace("//jsPackage.exe", string.Empty).Replace("\"", string.Empty).Replace("'", string.Empty).Trim();
                        if (!string.IsNullOrEmpty(tx))
                        {
                            string[] txArray = tx.Split(',');
                            if (txArray.Length > 0)
                            {
                                for (var i = 0; i < txArray.Length; i++)
                                {
                                    txArray[i] = txArray[i].Trim() + ".js";
                                }
                                Pack(txArray);
                            }
                            else
                            {
                                DetectCache(jsFilePath);
                            }
                        }
                        else
                        {
                            DetectCache(jsFilePath);
                        }
                    }
                    else
                    {
                        DetectCache(jsFilePath);
                    }
                    srC.Close();
                    
                }
                else
                {
                    DetectCache(jsFilePath);
                }
                
            }
        }
        static void BuilHtml(string[] args)
        {
            if (args.Length > 0)
            {
                
                string appPath = AppDomain.CurrentDomain.BaseDirectory;
                string htmlFilePath = appPath + "Panel";
                string htmlFileTemplate = appPath + "default.html";
                string htmlFileOutPath = appPath ;
                string htmlFileOutName = "default-static.html";
                
                DirectoryInfo dix = new DirectoryInfo(htmlFilePath);
                if (dix.Exists && File.Exists(htmlFileTemplate))
                {
                    StreamWriter sw = new StreamWriter(Path.Combine(htmlFileOutPath, htmlFileOutName), false);
                    StringBuilder sb1 = new StringBuilder();
                    StringBuilder sb2 = new StringBuilder();
                    foreach (string fileName in args)
                    {
                        string tfileName = fileName.Replace(".js", ".html");
                        StreamReader sr = new StreamReader(Path.Combine(htmlFilePath, tfileName));
                        string ts = sr.ReadToEnd();

                        string part1 = ts.Substring(ts.IndexOf("<!--#SignupPanel#-->"), ts.LastIndexOf("<!--#SignupPanel#-->") - ts.IndexOf("<!--#SignupPanel#-->"));
                        string part2 = ts.Substring(ts.IndexOf("<!--#SignupOverview#-->"), ts.LastIndexOf("<!--#SignupOverview#-->") - ts.IndexOf("<!--#SignupOverview#-->"));
                        sb1.Append(part1);
                        sb2.Append(part2);
                        sr.Close();
                    }

                    StreamReader srT = new StreamReader(htmlFileTemplate);
                    string tempHtml = srT.ReadToEnd();
                    
                    string jsScript = "<script src=\"Scripts/{0}?t={1}\" type=\"text/javascript\"></script>";
                    jsScript = string.Format(jsScript, jsFilename, DateTime.Now.ToString("yyyyMMddHHmmss"));
                    tempHtml = tempHtml
                        .Replace("<!--#$Signup Holder -->", sb1.ToString())
                        .Replace("<!--#$Signup_Overview Holder -->", sb2.ToString())
                        .Replace("<!--#$Signup_Panel_js Holder -->", jsScript)
                        .Replace("Signup.Core.js", "Signup.Core-1.4.static.js");


                    sw.Write(tempHtml);
                    sw.WriteLine("<!-- Page created time:{0} -->", DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss"));
                    
                    sw.Close();
                    sw.Dispose();
                    srT.Close();
                }
                else
                {
                    Console.WriteLine("no html");
                }

            }
        }
        private static void DetectCache(string jsFilePath)
        {
            #region Cache   Js
            DirectoryInfo di = new DirectoryInfo(jsFilePath);
            if (di.Exists)
            {
                FileInfo[] f = di.GetFiles("*.js");

                if (f.Length > 0)
                {

                    string[] a = new string[f.Length];
                    for (var i = 0; i < f.Length; i++)
                    {
                        a[i] = f[i].Name;
                    }
                    Pack(a);
                }
                else
                {
                    Console.WriteLine("no js file");
                }
            }
            else
            {
                Console.WriteLine("no js directory");
            }
            #endregion
        }
    }
}
