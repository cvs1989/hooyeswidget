using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Xml;
using Tweet.Core;
using OpenTSDK.Tencent;
using OpenTSDK.Tencent.Objects;

namespace Tweet
{
    class Program
    {
        static void Main(string[] args)
        {
            //Task.RunTestLog();
            sTimer.Run();
        }
        static void Test5()
        {

         #region Sina TO QQ
         XmlDocument sinaT=   Task.GetSinaUserTimeline("495718f5f9003448215b34482f4306fd", "23c7f928db274dd8c82bc00e99193db4", "9474059463");

         XmlNodeList x = sinaT.SelectNodes("/statuses/status");

         foreach (XmlNode xn in x)
         {
             decimal id = Util.GetXmlNodeValue<decimal>(xn.SelectSingleNode("id"));
             string text = Util.GetXmlNodeValue<string>(xn.SelectSingleNode("text")); ;

           TweetOperateResult QQresult=  Task.QQ(text, "2fcaf70e590d4b14a6a6d6e36771b77f", "181939ffc5901da5e84ccdf69bd0746f");

         }
            #endregion
        }
        static void Test4()
        {
            List<RelationEntity> lt = Relation.Get();
        }
        static void Test3()
        {

            Dictionary<string, string> dt = Dict.Get("QQ", "hooyes");

            string token = dt["Token"];

        }
        static void Test2()
        {
            RelationEntity et = new RelationEntity();
            et.App = "QQ";
            et.UserID = "hooyes";
            et.SubApp = "Sina";
            et.SubUserID = "1089048321";

           // RelationEntity et = new RelationEntity();

            Relation.Save(et);
            et.App = "QQ";
            et.UserID = "hooyes2";
            Relation.Save(et);
            //RelationEntity t = Relation.Get(et);


           // Relation.Delete(et);
        }
        static void Test1()
        {
            DictEntity dt = new DictEntity();
            dt.App = "QQ";
            dt.Key = "MaxTimeline";
            dt.UserID = "q83623011";
            dt.Value = "1303235118";
            Dict.Save(dt);
            
            string s= Dict.Get<string>(dt);
            Console.Write(s);
            Console.Read();
        }
    }
}
