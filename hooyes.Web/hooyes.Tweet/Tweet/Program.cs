using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Tweet.Core;

namespace Tweet
{
    class Program
    {
        static void Main(string[] args)
        {
            //string statusText = "天灰灰，会不会让我忘了你是谁";
            //T.QQ(statusText);
            //T.Sina(statusText);

            //db.MaxTimeline("hooyes", 1303230632);

            //sTimer.Run();
            //T.SinaTimeLine();
            //Console.WriteLine(db.MaxTimeline("hooyes"));

            //Console.Read();
            //T.SinaRun();
            //Test1();

            Task.Run();
            //Test1();

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
