-- DROP PROC [M_Update_Password]
GO
-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-06-24
-- Update date: 2013-06-24
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Update_Password]
    @AID INT ,
    @Password VARCHAR(50)
AS 
    DECLARE @NewPassword VARCHAR(50)  
    SET @NewPassword = sys.fn_VarBinToHexStr(HASHBYTES('md5',
                                                       @Password + 'lms'
                                                       + CONVERT(VARCHAR, @AID)))
    UPDATE  [Admin]
    SET     [Password] = @NewPassword,
	        UpdateDate = GETDATE()
    WHERE   AID = @AID

    RETURN 0
