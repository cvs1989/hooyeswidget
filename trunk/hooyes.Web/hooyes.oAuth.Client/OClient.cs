using System;
using System.Collections.Generic;
using System.Text;

namespace hooyes.OAuth.Client
{
    public class OClient
    {
        /// <summary>
        /// 表示是否已经通过验证
        /// </summary>
        public static bool IsAuthentication
        {
            get
            {
                return (MemCache.Get("_token") != null && MemCache.Get("_tokenSecret") != null && MemCache.Get("_user_id") != null);
            }
        }
        /// <summary>
        /// UserID
        /// </summary>
        public static string UserID
        {
            get
            {
                if (IsAuthentication)
                {
                    return (string)MemCache.Get("_user_id");
                }
                else
                {
                    return null;
                }
            }
        }
    }
}
