-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-16
-- Update date: 2013-09-16
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Update_BalanceTopupByCards]
    @MID INT ,
    @SN VARCHAR(50) ,
    @Code INT = 0 OUTPUT ,
    @Message NVARCHAR(4000) = '' OUTPUT
AS 
    DECLARE @Amount MONEY = 0
    SELECT  @Amount = Amount
    FROM    Cards
    WHERE   SN = @SN
            AND [Status] = 1
    IF @Amount > 0 
        BEGIN
            BEGIN TRY
                BEGIN TRANSACTION
                EXECUTE [Update_BalanceTopup] @MID, 0, @Amount
                UPDATE  Cards
                SET     [Status] = 10
                WHERE   SN = @SN
                EXECUTE [Update_AddTransactions] @MID = @MID,
                    @Amount = @Amount, @Cate = 1, @Source = @SN, @Memo = NULL
                SET @Code = 0
                SET @Message = 'success'
                COMMIT              
            END TRY
            BEGIN CATCH
                ROLLBACK   
                SET @Code = -100
                SET @Message = 'Transaction Error:' + ERROR_MESSAGE()                          
            END CATCH          
        END  
    ELSE 
        BEGIN
            SET @Code = -1
            SET @Message = 'SN Error'
        END   

    RETURN 0
