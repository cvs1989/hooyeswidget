using System;
using System.Collections.Generic;
using System.Text;

namespace com.hooyes.crc.Model
{
    public class CRCapply
    {
        private decimal _ID;
        public decimal ID
        {
            get { return _ID; }
            set { this._ID = value; }
        }
        private string _sn;
        /// <summary>
        /// 唯一标识 GUID
        /// </summary>
        public string sn
        {
            get { return _sn; }
            set { this._sn = value; }
        }
        private string _CompanyName;
        public string CompanyName
        {
            get { return _CompanyName; }
            set { this._CompanyName = value; }
        }
        private string _CompanyNameEn;
        public string CompanyNameEn
        {
            get { return _CompanyNameEn; }
            set { this._CompanyNameEn = value; }
        }
        private string _CompanyAddress;
        public string CompanyAddress
        {
            get { return _CompanyAddress; }
            set { this._CompanyAddress = value; }
        }
        private string _Contact;
        public string Contact
        {
            get { return _Contact; }
            set { this._Contact = value; }
        }
        private string _PostCode;
        public string PostCode
        {
            get { return _PostCode; }
            set { this._PostCode = value; }
        }
        private string _Phone;
        public string Phone
        {
            get { return _Phone; }
            set { this._Phone = value; }
        }
        private string _CellPhone;
        public string CellPhone
        {
            get { return _CellPhone; }
            set { this._CellPhone = value; }
        }
        private string _Fax;
        public string Fax
        {
            get { return _Fax; }
            set { this._Fax = value; }
        }
        private string _WebSite;
        public string WebSite
        {
            get { return _WebSite; }
            set { this._WebSite = value; }
        }
        private string _Email;
        public string Email
        {
            get { return _Email; }
            set { this._Email = value; }
        }
        private string _CompanyType;
        public string CompanyType
        {
            get { return _CompanyType; }
            set { this._CompanyType = value; }
        }
        private string _ProductType;
        public string ProductType
        {
            get { return _ProductType; }
            set { this._ProductType = value; }
        }
        private string _Vistors;
        public string Vistors
        {
            get { return _Vistors; }
            set { this._Vistors = value; }
        }
        private string _Suggestion;
        public string Suggestion
        {
            get { return _Suggestion; }
            set { this._Suggestion = value; }
        }
        private DateTime _RegisterTime;
        public DateTime RegisterTime
        {
            get { return _RegisterTime; }
            set { this._RegisterTime = value; }
        }
        private bool _Pay;
        /// <summary>
        /// 是否已交费
        /// </summary>
        public bool Pay
        {
            get { return _Pay; }
            set { this._Pay = value; }
        }
        private bool _Invoice;
        /// <summary>
        /// 是否需要发票
        /// </summary>
        public bool Invoice
        {
            get { return _Invoice; }
            set { this._Invoice = value; }
        }
    }
}
