using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web.Mvc;

namespace hooyes.Core.Mvc.Models
{
    public class movie
    {
        public string title { get; set; }
        public string director { get; set; }
        public string content { get; set; }
        public actor Actor { get; set; }
    }
    public class actor
    {
        public string name { get; set; }
        public string age { get; set; }
    }
}
