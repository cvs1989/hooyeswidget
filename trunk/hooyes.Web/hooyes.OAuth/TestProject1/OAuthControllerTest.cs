using OAuth.MVC.Library.Controllers;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using System;
using OAuth.Core.Interfaces;
using System.Web.Mvc;
using OAuth.Core;
using OAuth.Core.Provider;

namespace TestProject1
{
    
    
    /// <summary>
    ///This is a test class for OAuthControllerTest and is intended
    ///to contain all OAuthControllerTest Unit Tests
    ///</summary>
    [TestClass()]
    public class OAuthControllerTest
    {


        private TestContext testContextInstance;

        /// <summary>
        ///Gets or sets the test context which provides
        ///information about and functionality for the current test run.
        ///</summary>
        public TestContext TestContext
        {
            get
            {
                return testContextInstance;
            }
            set
            {
                testContextInstance = value;
            }
        }

        #region Additional test attributes
        // 
        //You can use the following additional attributes as you write your tests:
        //
        //Use ClassInitialize to run code before running the first test in the class
        //[ClassInitialize()]
        //public static void MyClassInitialize(TestContext testContext)
        //{
        //}
        //
        //Use ClassCleanup to run code after all tests in a class have run
        //[ClassCleanup()]
        //public static void MyClassCleanup()
        //{
        //}
        //
        //Use TestInitialize to run code before running each test
        //[TestInitialize()]
        //public void MyTestInitialize()
        //{
        //}
        //
        //Use TestCleanup to run code after each test has run
        //[TestCleanup()]
        //public void MyTestCleanup()
        //{
        //}
        //
        #endregion


        /// <summary>
        ///A test for RequestToken
        ///</summary>
        [TestMethod()]
        public void RequestTokenTest()
        {
            //IOAuthContextBuilder oAuthContextBuilder = new OAuthContextBuilder(); // TODO: Initialize to an appropriate value
            //IOAuthProvider oAuthProvider = new OAuthProvider(); // TODO: Initialize to an appropriate value
            //OAuthController target = new OAuthController(oAuthContextBuilder, oAuthProvider); // TODO: Initialize to an appropriate value
            //ActionResult expected = null; // TODO: Initialize to an appropriate value
            //ActionResult actual;
            //actual = target.RequestToken();
            //Assert.AreEqual(expected, actual);
            //Assert.Inconclusive("Verify the correctness of this test method.");
        }
    }
}
