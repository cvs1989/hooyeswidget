using System;
using System.Collections.Generic;
using System.Text;

namespace Tweet.Core
{
   public class RelationEntity
    {
        public int ID { get; set; }
        public string App { get; set; }
        public string UserID { get; set; }
        public string SubApp { get; set; }
        public string SubUserID { get; set; }
    }
}
