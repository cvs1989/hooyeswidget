using System;
using System.Collections;
using System.Collections.Generic;
using System.Text;
using System.IO;
using Thrift;
using Thrift.Collections;
using Thrift.Protocol;
using Thrift.Transport;


namespace Hbase.Library
{
    [Serializable]
    public partial class IOError : Exception, TBase
    {
        private string _message;

        public string message
        {
            get
            {
                return _message;
            }
            set
            {
                __isset.message = true;
                this._message = value;
            }
        }


        public Isset __isset;
        [Serializable]
        public struct Isset
        {
            public bool message;
        }

        public IOError()
        {
        }

        public void Read(TProtocol iprot)
        {
            TField field;
            iprot.ReadStructBegin();
            while (true)
            {
                field = iprot.ReadFieldBegin();
                if (field.Type == TType.Stop)
                {
                    break;
                }
                switch (field.ID)
                {
                    case 1:
                        if (field.Type == TType.String)
                        {
                            message = iprot.ReadString();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    default:
                        TProtocolUtil.Skip(iprot, field.Type);
                        break;
                }
                iprot.ReadFieldEnd();
            }
            iprot.ReadStructEnd();
        }

        public void Write(TProtocol oprot)
        {
            TStruct struc = new TStruct("IOError");
            oprot.WriteStructBegin(struc);
            TField field = new TField();
            if (Message != null && __isset.message)
            {
                field.Name = "message";
                field.Type = TType.String;
                field.ID = 1;
                oprot.WriteFieldBegin(field);
                oprot.WriteString(Message);
                oprot.WriteFieldEnd();
            }
            oprot.WriteFieldStop();
            oprot.WriteStructEnd();
        }

        public override string ToString()
        {
            StringBuilder sb = new StringBuilder("IOError(");
            sb.Append("Message: ");
            sb.Append(Message);
            sb.Append(")");
            return sb.ToString();
        }

    }
}