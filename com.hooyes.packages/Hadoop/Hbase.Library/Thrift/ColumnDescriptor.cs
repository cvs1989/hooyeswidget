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
    public partial class ColumnDescriptor : TBase
    {
        private byte[] _name;
        private int _maxVersions;
        private string _compression;
        private bool _inMemory;
        private string _bloomFilterType;
        private int _bloomFilterVectorSize;
        private int _bloomFilterNbHashes;
        private bool _blockCacheEnabled;
        private int _timeToLive;

        public byte[] Name
        {
            get
            {
                return _name;
            }
            set
            {
                __isset.name = true;
                this._name = value;
            }
        }

        public int MaxVersions
        {
            get
            {
                return _maxVersions;
            }
            set
            {
                __isset.maxVersions = true;
                this._maxVersions = value;
            }
        }

        public string Compression
        {
            get
            {
                return _compression;
            }
            set
            {
                __isset.compression = true;
                this._compression = value;
            }
        }

        public bool InMemory
        {
            get
            {
                return _inMemory;
            }
            set
            {
                __isset.inMemory = true;
                this._inMemory = value;
            }
        }

        public string BloomFilterType
        {
            get
            {
                return _bloomFilterType;
            }
            set
            {
                __isset.bloomFilterType = true;
                this._bloomFilterType = value;
            }
        }

        public int BloomFilterVectorSize
        {
            get
            {
                return _bloomFilterVectorSize;
            }
            set
            {
                __isset.bloomFilterVectorSize = true;
                this._bloomFilterVectorSize = value;
            }
        }

        public int BloomFilterNbHashes
        {
            get
            {
                return _bloomFilterNbHashes;
            }
            set
            {
                __isset.bloomFilterNbHashes = true;
                this._bloomFilterNbHashes = value;
            }
        }

        public bool BlockCacheEnabled
        {
            get
            {
                return _blockCacheEnabled;
            }
            set
            {
                __isset.blockCacheEnabled = true;
                this._blockCacheEnabled = value;
            }
        }

        public int TimeToLive
        {
            get
            {
                return _timeToLive;
            }
            set
            {
                __isset.timeToLive = true;
                this._timeToLive = value;
            }
        }


        public Isset __isset;
        [Serializable]
        public struct Isset
        {
            public bool name;
            public bool maxVersions;
            public bool compression;
            public bool inMemory;
            public bool bloomFilterType;
            public bool bloomFilterVectorSize;
            public bool bloomFilterNbHashes;
            public bool blockCacheEnabled;
            public bool timeToLive;
        }

        public ColumnDescriptor()
        {
            this._maxVersions = 3;
            this._compression = "NONE";
            this._inMemory = false;
            this._bloomFilterType = "NONE";
            this._bloomFilterVectorSize = 0;
            this._bloomFilterNbHashes = 0;
            this._blockCacheEnabled = false;
            this._timeToLive = -1;
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
                            Name = iprot.ReadBinary();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 2:
                        if (field.Type == TType.I32)
                        {
                            MaxVersions = iprot.ReadI32();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 3:
                        if (field.Type == TType.String)
                        {
                            Compression = iprot.ReadString();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 4:
                        if (field.Type == TType.Bool)
                        {
                            InMemory = iprot.ReadBool();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 5:
                        if (field.Type == TType.String)
                        {
                            BloomFilterType = iprot.ReadString();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 6:
                        if (field.Type == TType.I32)
                        {
                            BloomFilterVectorSize = iprot.ReadI32();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 7:
                        if (field.Type == TType.I32)
                        {
                            BloomFilterNbHashes = iprot.ReadI32();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 8:
                        if (field.Type == TType.Bool)
                        {
                            BlockCacheEnabled = iprot.ReadBool();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 9:
                        if (field.Type == TType.I32)
                        {
                            TimeToLive = iprot.ReadI32();
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
            TStruct struc = new TStruct("ColumnDescriptor");
            oprot.WriteStructBegin(struc);
            TField field = new TField();
            if (Name != null && __isset.name)
            {
                field.Name = "name";
                field.Type = TType.String;
                field.ID = 1;
                oprot.WriteFieldBegin(field);
                oprot.WriteBinary(Name);
                oprot.WriteFieldEnd();
            }
            if (__isset.maxVersions)
            {
                field.Name = "maxVersions";
                field.Type = TType.I32;
                field.ID = 2;
                oprot.WriteFieldBegin(field);
                oprot.WriteI32(MaxVersions);
                oprot.WriteFieldEnd();
            }
            if (Compression != null && __isset.compression)
            {
                field.Name = "compression";
                field.Type = TType.String;
                field.ID = 3;
                oprot.WriteFieldBegin(field);
                oprot.WriteString(Compression);
                oprot.WriteFieldEnd();
            }
            if (__isset.inMemory)
            {
                field.Name = "inMemory";
                field.Type = TType.Bool;
                field.ID = 4;
                oprot.WriteFieldBegin(field);
                oprot.WriteBool(InMemory);
                oprot.WriteFieldEnd();
            }
            if (BloomFilterType != null && __isset.bloomFilterType)
            {
                field.Name = "bloomFilterType";
                field.Type = TType.String;
                field.ID = 5;
                oprot.WriteFieldBegin(field);
                oprot.WriteString(BloomFilterType);
                oprot.WriteFieldEnd();
            }
            if (__isset.bloomFilterVectorSize)
            {
                field.Name = "bloomFilterVectorSize";
                field.Type = TType.I32;
                field.ID = 6;
                oprot.WriteFieldBegin(field);
                oprot.WriteI32(BloomFilterVectorSize);
                oprot.WriteFieldEnd();
            }
            if (__isset.bloomFilterNbHashes)
            {
                field.Name = "bloomFilterNbHashes";
                field.Type = TType.I32;
                field.ID = 7;
                oprot.WriteFieldBegin(field);
                oprot.WriteI32(BloomFilterNbHashes);
                oprot.WriteFieldEnd();
            }
            if (__isset.blockCacheEnabled)
            {
                field.Name = "blockCacheEnabled";
                field.Type = TType.Bool;
                field.ID = 8;
                oprot.WriteFieldBegin(field);
                oprot.WriteBool(BlockCacheEnabled);
                oprot.WriteFieldEnd();
            }
            if (__isset.timeToLive)
            {
                field.Name = "timeToLive";
                field.Type = TType.I32;
                field.ID = 9;
                oprot.WriteFieldBegin(field);
                oprot.WriteI32(TimeToLive);
                oprot.WriteFieldEnd();
            }
            oprot.WriteFieldStop();
            oprot.WriteStructEnd();
        }

        public override string ToString()
        {
            StringBuilder sb = new StringBuilder("ColumnDescriptor(");
            sb.Append("Name: ");
            sb.Append(Name);
            sb.Append(",MaxVersions: ");
            sb.Append(MaxVersions);
            sb.Append(",Compression: ");
            sb.Append(Compression);
            sb.Append(",InMemory: ");
            sb.Append(InMemory);
            sb.Append(",BloomFilterType: ");
            sb.Append(BloomFilterType);
            sb.Append(",BloomFilterVectorSize: ");
            sb.Append(BloomFilterVectorSize);
            sb.Append(",BloomFilterNbHashes: ");
            sb.Append(BloomFilterNbHashes);
            sb.Append(",BlockCacheEnabled: ");
            sb.Append(BlockCacheEnabled);
            sb.Append(",TimeToLive: ");
            sb.Append(TimeToLive);
            sb.Append(")");
            return sb.ToString();
        }

    }
}