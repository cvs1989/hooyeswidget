-- DROP PROC [M_Import_MessageQueue]
GO
-- =============================================
-- Version:     2.0.0.2
-- Author:		hooyes
-- Create date: 2014-07-28
-- Update date: 2014-07-30
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Import_MessageQueue]
    @MsgID INT ,
    @Phone VARCHAR(50) ,
    @Flag INT = 0 ,
    @MID INT = 0 ,
    @DayID INT = 0 ,
    @UpdateDate DATETIME = NULL ,
    @Code INT = 0 OUTPUT ,
    @Message VARCHAR(200) = '' OUTPUT
AS 
    IF @Phone != ''
        AND @Phone IS NOT NULL 
        BEGIN 
            INSERT  INTO [dbo].[MessageQueue]
                    ( [MsgID] ,
                      [Phone] ,
                      [Flag] ,
                      [MID] ,
                      [DayID] ,
                      [CreateDate] ,
                      [UpdateDate]
                    )
            VALUES  ( @MsgID ,
                      @Phone ,
                      @Flag ,
                      @MID ,
                      @DayID ,
                      GETDATE() ,
                      @UpdateDate
                    )
        END
    ELSE 
        BEGIN
	     SET @Message ='Phone is invalid'
        END          

    RETURN 0
