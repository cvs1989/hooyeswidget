namespace com.hooyes.widget
{
    using System;

    public class translate
    {
        private com.hooyes.widget.responseData _responseData;
        private string _responseDetails;
        private string _responseStatus;

        public com.hooyes.widget.responseData responseData
        {
            get
            {
                return this._responseData;
            }
            set
            {
                this._responseData = value;
            }
        }

        public string responseDetails
        {
            get
            {
                return this._responseDetails;
            }
            set
            {
                this._responseDetails = value;
            }
        }

        public string responseStatus
        {
            get
            {
                return this._responseStatus;
            }
            set
            {
                this._responseStatus = value;
            }
        }
    }
}

