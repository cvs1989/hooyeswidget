using System;
using System.Collections.Generic;
using System.Web;
using System.Text;

namespace Tweet.Core
{
    public class WebUtility
    {
        public static bool SaveRelation()
        {
            if (HttpContext.Current.Session["QQ_user_id"] != null && HttpContext.Current.Session["Sina_user_id"] != null)
            {
                RelationEntity et = new RelationEntity();
                et.App = "QQ";
                et.UserID = (string)HttpContext.Current.Session["QQ_user_id"];
                et.SubApp = "Sina";
                et.SubUserID = (string)HttpContext.Current.Session["Sina_user_id"];
                // RelationEntity et = new RelationEntity();
                Relation.Save(et);
            }
            return true;
        }
    }
}
