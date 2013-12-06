-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-11-27
-- Update date: 2013-11-27
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Check_LoginID]
    @LoginID VARCHAR(30) = NULL ,
    @Code INT = 0 OUTPUT ,
    @Message VARCHAR(200) = '' OUTPUT
AS 
    DECLARE @MID INT = 0
    SELECT  @MID = MID
    FROM    Member
    WHERE   [Login] = @LoginID
    IF @MID > 0 
        BEGIN
            SET @Code = 0
            SET @Message = 'Login Exists'
        END 
    ELSE 
        BEGIN
            SET @Code = 1
            SET @Message = 'Login Not Exists'	
        END  
    RETURN @MID
