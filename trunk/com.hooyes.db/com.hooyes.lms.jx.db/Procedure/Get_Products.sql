-- =============================================
-- Version:     1.0.0.2
-- Author:		hooyes
-- Create date: 2013-09-14
-- Update date: 2013-09-22
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Products] 
    @MID INT = 0
AS 
    SELECT  p.ID ,
            p.PID ,
			P.Price,
            p.Name ,
			p.Sort ,
            p.Memo ,
            MyID = ISNULL(myP.ID, 0)
    FROM    dbo.Products P
            LEFT JOIN dbo.My_Products myP ON P.PID = myP.PID
                                             AND myP.MID = @MID
	ORDER BY p.Sort DESC
    RETURN 0
