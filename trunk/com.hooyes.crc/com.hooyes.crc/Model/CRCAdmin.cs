using System;
using System.Collections.Generic;
using System.Text;

namespace com.hooyes.crc.Model
{
    public class CRCAdmin
    {
        private decimal _ID;
        public decimal ID
        {
            get { return _ID; }
            set { this._ID = value; }
        }
        private string _sn;
        /// <summary>
        /// Œ®“ª±Í ∂ GUID
        /// </summary>
        public string sn
        {
            get { return _sn; }
            set { this._sn = value; }
        }
        private string _UserName;
        public string UserName
        {
            get { return _UserName; }
            set { this._UserName = value; }
        }
        private string _PassWord;
        public string PassWord
        {
            get { return _PassWord; }
            set { this._PassWord = value; }
        }
    }
}
