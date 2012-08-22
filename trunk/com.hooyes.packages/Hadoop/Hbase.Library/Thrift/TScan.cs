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
    public partial class TScan : TBase
    {
        private byte[] _startRow;
        private byte[] _stopRow;
        private long _timestamp;
        private List<byte[]> _columns;
        private int _caching;
        private byte[] _filterString;

        public byte[] StartRow
        {
            get
            {
                return _startRow;
            }
            set
            {
                __isset.startRow = true;
                this._startRow = value;
            }
        }

        public byte[] StopRow
        {
            get
            {
                return _stopRow;
            }
            set
            {
                __isset.stopRow = true;
                this._stopRow = value;
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

        public List<byte[]> Columns
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

        public int Caching
        {
            get
            {
                return _caching;
            }
            set
            {
                __isset.caching = true;
                this._caching = value;
            }
        }

        public byte[] FilterString
        {
            get
            {
                return _filterString;
            }
            set
            {
                __isset.filterString = true;
                this._filterString = value;
            }
        }


        public Isset __isset;
        [Serializable]
        public struct Isset
        {
            public bool startRow;
            public bool stopRow;
            public bool timestamp;
            public bool columns;
            public bool caching;
            public bool filterString;
        }

        public TScan()
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
                            StartRow = iprot.ReadBinary();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 2:
                        if (field.Type == TType.String)
                        {
                            StopRow = iprot.ReadBinary();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 3:
                        if (field.Type == TType.I64)
                        {
                            Timestamp = iprot.ReadI64();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 4:
                        if (field.Type == TType.List)
                        {
                            {
                                Columns = new List<byte[]>();
                                TList _list9 = iprot.ReadListBegin();
                                for (int _i10 = 0; _i10 < _list9.Count; ++_i10)
                                {
                                    byte[] _elem11 = null;
                                    _elem11 = iprot.ReadBinary();
                                    Columns.Add(_elem11);
                                }
                                iprot.ReadListEnd();
                            }
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 5:
                        if (field.Type == TType.I32)
                        {
                            Caching = iprot.ReadI32();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 6:
                        if (field.Type == TType.String)
                        {
                            FilterString = iprot.ReadBinary();
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
            TStruct struc = new TStruct("TScan");
            oprot.WriteStructBegin(struc);
            TField field = new TField();
            if (StartRow != null && __isset.startRow)
            {
                field.Name = "startRow";
                field.Type = TType.String;
                field.ID = 1;
                oprot.WriteFieldBegin(field);
                oprot.WriteBinary(StartRow);
                oprot.WriteFieldEnd();
            }
            if (StopRow != null && __isset.stopRow)
            {
                field.Name = "stopRow";
                field.Type = TType.String;
                field.ID = 2;
                oprot.WriteFieldBegin(field);
                oprot.WriteBinary(StopRow);
                oprot.WriteFieldEnd();
            }
            if (__isset.timestamp)
            {
                field.Name = "timestamp";
                field.Type = TType.I64;
                field.ID = 3;
                oprot.WriteFieldBegin(field);
                oprot.WriteI64(Timestamp);
                oprot.WriteFieldEnd();
            }
            if (Columns != null && __isset.columns)
            {
                field.Name = "columns";
                field.Type = TType.List;
                field.ID = 4;
                oprot.WriteFieldBegin(field);
                {
                    oprot.WriteListBegin(new TList(TType.String, Columns.Count));
                    foreach (byte[] _iter12 in Columns)
                    {
                        oprot.WriteBinary(_iter12);
                    }
                    oprot.WriteListEnd();
                }
                oprot.WriteFieldEnd();
            }
            if (__isset.caching)
            {
                field.Name = "caching";
                field.Type = TType.I32;
                field.ID = 5;
                oprot.WriteFieldBegin(field);
                oprot.WriteI32(Caching);
                oprot.WriteFieldEnd();
            }
            if (FilterString != null && __isset.filterString)
            {
                field.Name = "filterString";
                field.Type = TType.String;
                field.ID = 6;
                oprot.WriteFieldBegin(field);
                oprot.WriteBinary(FilterString);
                oprot.WriteFieldEnd();
            }
            oprot.WriteFieldStop();
            oprot.WriteStructEnd();
        }

        public override string ToString()
        {
            StringBuilder sb = new StringBuilder("TScan(");
            sb.Append("StartRow: ");
            sb.Append(StartRow);
            sb.Append(",StopRow: ");
            sb.Append(StopRow);
            sb.Append(",Timestamp: ");
            sb.Append(Timestamp);
            sb.Append(",Columns: ");
            sb.Append(Columns);
            sb.Append(",Caching: ");
            sb.Append(Caching);
            sb.Append(",FilterString: ");
            sb.Append(FilterString);
            sb.Append(")");
            return sb.ToString();
        }

    }
}