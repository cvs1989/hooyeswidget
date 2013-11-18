-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-20
-- Update date: 2013-09-20
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_Order]
    @MID INT ,
    @ID INT ,
    @Status INT ,
    @Memo VARCHAR(200) = NULL ,
    @Code INT = 0 OUTPUT ,
    @Message NVARCHAR(4000) = '' OUTPUT
AS 
    UPDATE  Orders
    SET     [Status] = @Status ,
            Memo = @Memo
    WHERE   MID = @MID
            AND ID = @ID
    RETURN 0
