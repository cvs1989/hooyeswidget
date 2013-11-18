-- =============================================
-- Version:     1.0.0.2
-- Author:		hooyes
-- Create date: 2013-09-14
-- Update date: 2013-10-04
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Order] 
    @MID INT, 
	@ID INT = 0
AS 
    SELECT  *
    FROM    Orders
    WHERE   MID = @MID
            AND ( @ID = 0
                  OR ID = @ID
                )
    RETURN 0
