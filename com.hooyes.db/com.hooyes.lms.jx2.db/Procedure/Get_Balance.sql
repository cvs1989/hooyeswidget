-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-15
-- Update date: 2013-09-15
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Balance]
    @MID INT = 0 ,
    @Amount MONEY = 0 OUTPUT ,
    @Cash MONEY = 0 OUTPUT ,
    @Rebate MONEY = 0 OUTPUT
AS 
    SELECT  @Amount = Amount ,
            @Cash = Cash ,
            @Rebate = Rebate
    FROM    Balance
    WHERE   MID = @MID

	SET @Amount = ISNULL(@Amount,0)
	SET @Cash = ISNULL(@Cash,0)
	SET @Rebate = ISNULL(@Rebate,0)

    RETURN 0
