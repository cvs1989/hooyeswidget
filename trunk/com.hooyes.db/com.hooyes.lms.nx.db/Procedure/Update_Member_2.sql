-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2014-05-21
-- Update date: 2014-05-21
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_Member_2]
    @MID INT = 0 ,
    @Phone VARCHAR(20) = '' ,
    @Company VARCHAR(50) = '' ,
    @IDCert VARCHAR(30) = '' ,
    @RegionCode INT = 0 ,
    @Level INT = 0 ,
    @Code INT = 0 OUTPUT ,
    @Message VARCHAR(200) = '' OUTPUT
AS 
    UPDATE  Member
    SET     Phone = @Phone ,
            Company = @Company ,
            IDCert = @IDCert ,
            RegionCode = @RegionCode ,
            [Level] = @Level
    WHERE   MID = @MID 

    IF @@ERROR = 0 
        BEGIN
            SET @Code = 0
            SET @Message = 'Success'
        END
    ELSE 
        BEGIN
            SET @Code = @@ERROR
            SET @Message = 'Success'
        END 

    RETURN 0
