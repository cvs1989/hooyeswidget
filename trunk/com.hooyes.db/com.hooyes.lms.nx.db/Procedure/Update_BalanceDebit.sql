-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-16
-- Update date: 2013-09-16
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_BalanceDebit]
    @MID INT ,
    @Amount MONEY = 0 ,
    @Code INT = 0 OUTPUT ,
    @Message VARCHAR(100) = '' OUTPUT
AS 
    SET @Amount = ABS(@Amount)  -- 只能正数
    DECLARE @Balance_Amount MONEY =0,
        @B_Amount MONEY , /* B_  Balance Detail */
        @B_Cash MONEY ,
        @B_Rebate MONEY
    EXECUTE [Get_Balance] @MID = @MID, @Amount = @B_Amount OUTPUT,
        @Cash = @B_Cash OUTPUT, @Rebate = @B_Rebate OUTPUT
  
    /* 若余额是 0 金额是 0, 需要产生一条记录*/ 
    IF @B_Amount = 0
        AND @Amount = 0 
        BEGIN
            IF NOT EXISTS ( SELECT  1
                            FROM    Balance
                            WHERE   MID = @MID ) 
                BEGIN
                    EXECUTE [Update_BalanceTopup] @MID, 0, 0     
                END      
        END   
	
    IF @B_Amount >= @Amount 
        BEGIN
            IF @B_Rebate >= @Amount 
                BEGIN
                    UPDATE  Balance
                    SET     Rebate = Rebate - @Amount ,
                            Amount = Amount - @Amount,
							@Balance_Amount = Amount - @Amount,
							UpdateDate = GETDATE()
                    WHERE   MID = @MID
                END
				ELSE
				BEGIN
                    UPDATE  Balance
                    SET     Rebate = 0 ,
					        Cash = Cash - (@Amount - Rebate),
                            Amount = Amount - @Amount,
							@Balance_Amount = Amount - @Amount,
							UpdateDate = GETDATE()
                    WHERE   MID = @MID				  
				END  

            SET @Code = 0
            SET @Message = 'success'
        END
    ELSE 
        BEGIN
            SET @Code = -1
            SET @Message = 'insufficient balance'
        END  
	 
    RETURN @Balance_Amount
