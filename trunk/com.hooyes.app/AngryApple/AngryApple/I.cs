using com.hooyes.app.AngryApple.SR;
namespace com.hooyes.app.AngryApple
{
    public class I
    {
        public static R S(M1 m)
        {
            var r = new R();
            var c = new Service1Client();
            var r1 = c.I(m);
            r.Code = r1.Code;
            r.Value = r1.Value;
            r.SN = r1.SN;
            r.Message = r1.Message;
            c.Close();
            return r;
        }
        public static R V(string k)
        {
            var r = new R();
            var c = new Service1Client();
            var r1 = c.V(k);
            r.Code = r1.Code;
            r.Value = r1.Value;
            r.SN = r1.SN;
            r.Message = r1.Message;
            c.Close();
            return r;
        }


    }
}
