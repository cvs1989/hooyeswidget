-- =============================================
-- Version: 1.0.0.1
-- Author:		hooyes
-- Create date: 2013-10-19
-- Update date: 2013-10-19
-- Desc:
-- =============================================
CREATE VIEW [dbo].[v_m_incomes]
AS
    SELECT  [Date] = CONVERT(INT, CONVERT(VARCHAR(10), UpdateDate, 112)) ,
            [Amount] = SUM(Cash) ,
            [Count] = COUNT(1) ,
            [Avg] = AVG(cash)
    FROM    Orders
    WHERE   [Status] = 10
    GROUP BY CONVERT(VARCHAR(10), UpdateDate, 112)
