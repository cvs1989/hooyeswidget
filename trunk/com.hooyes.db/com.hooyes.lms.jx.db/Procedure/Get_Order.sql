-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-14
-- Update date: 2013-09-15
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Order] 
      @MID INT, 
	  @ID INT
AS 
    SELECT  *
    FROM    Orders
    WHERE   ID = @ID
            AND MID = @MID
    RETURN 0
