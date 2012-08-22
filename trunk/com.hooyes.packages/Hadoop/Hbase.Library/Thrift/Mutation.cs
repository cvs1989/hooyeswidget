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
    public partial class Mutation : TBase
    {
        private bool _isDelete;
        private byte[] _column;
        private byte[] _value;

        public bool IsDelete
        {
            get
            {
                return _isDelete;
            }
            set
            {
                __isset.isDelete = true;
                this._isDelete = value;
            }
        }

        public byte[] Column
        {
            get
            {
                return _column;
            }
            set
            {
                __isset.column = true;
                this._column = value;
            }
        }

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


        public Isset __isset;
        [Serializable]
        public struct Isset
        {
            public bool isDelete;
            public bool column;
            public bool value;
        }

        public Mutation()
        {
            this._isDelete = false;
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
                        if (field.Type == TType.Bool)
                        {
                            IsDelete = iprot.ReadBool();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 2:
                        if (field.Type == TType.String)
                        {
                            Column = iprot.ReadBinary();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 3:
                        if (field.Type == TType.String)
                        {
                            Value = iprot.ReadBinary();
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
            TStruct struc = new TStruct("Mutation");
            oprot.WriteStructBegin(struc);
            TField field = new TField();
            if (__isset.isDelete)
            {
                field.Name = "isDelete";
                field.Type = TType.Bool;
                field.ID = 1;
                oprot.WriteFieldBegin(field);
                oprot.WriteBool(IsDelete);
                oprot.WriteFieldEnd();
            }
            if (Column != null && __isset.column)
            {
                field.Name = "column";
                field.Type = TType.String;
                field.ID = 2;
                oprot.WriteFieldBegin(field);
                oprot.WriteBinary(Column);
                oprot.WriteFieldEnd();
            }
            if (Value != null && __isset.value)
            {
                field.Name = "value";
                field.Type = TType.String;
                field.ID = 3;
                oprot.WriteFieldBegin(field);
                oprot.WriteBinary(Value);
                oprot.WriteFieldEnd();
            }
            oprot.WriteFieldStop();
            oprot.WriteStructEnd();
        }

        public override string ToString()
        {
            StringBuilder sb = new StringBuilder("Mutation(");
            sb.Append("IsDelete: ");
            sb.Append(IsDelete);
            sb.Append(",Column: ");
            sb.Append(Column);
            sb.Append(",Value: ");
            sb.Append(Value);
            sb.Append(")");
            return sb.ToString();
        }

    }
}