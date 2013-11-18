-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-16
-- Update date: 2013-09-16
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_BalanceTopup]
    @MID INT ,
    @Cash MONEY = 0 ,
    @Rebate MONEY = 0 ,
    @Code INT = 0 OUTPUT ,
    @Message VARCHAR(100) = '' OUTPUT
AS 
    DECLARE @Balance_Amount MONEY = 0
    UPDATE  Balance
    SET     Cash = Cash + @Cash ,
            Rebate = Rebate + @Rebate ,
            Amount = Cash + Rebate + @Cash + @Rebate ,
            @Balance_Amount = Cash + Rebate + @Cash + @Rebate ,
            UpdateDate = GETDATE()
    WHERE   MID = @MID
    IF @@ROWCOUNT = 0 
        BEGIN
            SET @Balance_Amount = @Cash + @Rebate
            INSERT  INTO Balance
                    ( MID ,
                      Amount ,
                      Cash ,
                      Rebate ,
                      UpdateDate
                    )
            VALUES  ( @MID ,
                      @Balance_Amount ,
                      @Cash ,
                      @Rebate ,
                      GETDATE()
                    )
        END  

    RETURN @Balance_Amount
