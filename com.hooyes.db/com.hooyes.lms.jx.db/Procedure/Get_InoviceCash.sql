-- =============================================
-- Version:     1.0.0.2
-- Author:		hooyes
-- Create date: 2013-09-25
-- Update date: 2013-09-27
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_InoviceCash]
    @MID INT = 0 ,
    @Code INT = 0 OUTPUT ,
    @Message NVARCHAR(4000) = '' OUTPUT
AS 
    DECLARE @Cash MONEY = 0 ,
        @InvoiceAmount MONEY = 0,
		@RetAmount MONEY = 0
	
    SELECT  @Cash = ISNULL(SUM(Cash),0)
    FROM    dbo.Orders
    WHERE   MID = @MID
            AND [Status] = 10

    SELECT  @InvoiceAmount = ISNULL(SUM(Amount),0)
    FROM    dbo.Invoice
    WHERE   MID = @MID

	SET @RetAmount = @Cash - @InvoiceAmount
	IF @RetAmount < 0 
	   SET @RetAmount = 0


    SET @Code = 0
    SET @Message = 'success'
	 
    SELECT  RetAmount = @RetAmount
    RETURN @RetAmount
