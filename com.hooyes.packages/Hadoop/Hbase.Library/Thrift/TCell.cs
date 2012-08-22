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
    public partial class TCell : TBase
    {
        private byte[] _value;
        private long _timestamp;

        public byte[] Value
        {
            get
            {
                return _value;
            }
            set
            {
                __isset.value = true;
                this._value = value;
            }
        }

        public long Timestamp
        {
            get
            {
                return _timestamp;
            }
            set
            {
                __isset.timestamp = true;
                this._timestamp = value;
            }
        }


        public Isset __isset;
        [Serializable]
        public struct Isset
        {
            public bool value;
            public bool timestamp;
        }

        public TCell()
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
                            Value = iprot.ReadBinary();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 2:
                        if (field.Type == TType.I64)
                        {
                            Timestamp = iprot.ReadI64();
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
            TStruct struc = new TStruct("TCell");
            oprot.WriteStructBegin(struc);
            TField field = new TField();
            if (Value != null && __isset.value)
            {
                field.Name = "value";
                field.Type = TType.String;
                field.ID = 1;
                oprot.WriteFieldBegin(field);
                oprot.WriteBinary(Value);
                oprot.WriteFieldEnd();
            }
            if (__isset.timestamp)
            {
                field.Name = "timestamp";
                field.Type = TType.I64;
                field.ID = 2;
                oprot.WriteFieldBegin(field);
                oprot.WriteI64(Timestamp);
                oprot.WriteFieldEnd();
            }
            oprot.WriteFieldStop();
            oprot.WriteStructEnd();
        }

        public override string ToString()
        {
            StringBuilder sb = new StringBuilder("TCell(");
            sb.Append("Value: ");
            sb.Append(Value);
            sb.Append(",Timestamp: ");
            sb.Append(Timestamp);
            sb.Append(")");
            return sb.ToString();
        }

    }
}


