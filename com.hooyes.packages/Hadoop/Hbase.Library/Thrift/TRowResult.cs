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
    public partial class TRowResult : TBase
    {
        private byte[] _row;
        private Dictionary<byte[], TCell> _columns;

        public byte[] Row
        {
            get
            {
                return _row;
            }
            set
            {
                __isset.row = true;
                this._row = value;
            }
        }

        public Dictionary<byte[], TCell> Columns
        {
            get
            {
                return _columns;
            }
            set
            {
                __isset.columns = true;
                this._columns = value;
            }
        }


        public Isset __isset;
        [Serializable]
        public struct Isset
        {
            public bool row;
            public bool columns;
        }

        public TRowResult()
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
                            Row = iprot.ReadBinary();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 2:
                        if (field.Type == TType.Map)
                        {
                            {
                                Columns = new Dictionary<byte[], TCell>();
                                TMap _map4 = iprot.ReadMapBegin();
                                for (int _i5 = 0; _i5 < _map4.Count; ++_i5)
                                {
                                    byte[] _key6;
                                    TCell _val7;
                                    _key6 = iprot.ReadBinary();
                                    _val7 = new TCell();
                                    _val7.Read(iprot);
                                    Columns[_key6] = _val7;
                                }
                                iprot.ReadMapEnd();
                            }
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
            TStruct struc = new TStruct("TRowResult");
            oprot.WriteStructBegin(struc);
            TField field = new TField();
            if (Row != null && __isset.row)
            {
                field.Name = "row";
                field.Type = TType.String;
                field.ID = 1;
                oprot.WriteFieldBegin(field);
                oprot.WriteBinary(Row);
                oprot.WriteFieldEnd();
            }
            if (Columns != null && __isset.columns)
            {
                field.Name = "columns";
                field.Type = TType.Map;
                field.ID = 2;
                oprot.WriteFieldBegin(field);
                {
                    oprot.WriteMapBegin(new TMap(TType.String, TType.Struct, Columns.Count));
                    foreach (byte[] _iter8 in Columns.Keys)
                    {
                        oprot.WriteBinary(_iter8);
                        Columns[_iter8].Write(oprot);
                    }
                    oprot.WriteMapEnd();
                }
                oprot.WriteFieldEnd();
            }
            oprot.WriteFieldStop();
            oprot.WriteStructEnd();
        }

        public override string ToString()
        {
            StringBuilder sb = new StringBuilder("TRowResult(");
            sb.Append("Row: ");
            sb.Append(Row);
            sb.Append(",Columns: ");
            sb.Append(Columns);
            sb.Append(")");
            return sb.ToString();
        }

    }
}