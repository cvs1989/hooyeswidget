-- =============================================
-- Version:     1.0.0.5
-- Author:		hooyes
-- Create date: 2012-04-21
-- Update date: 2014-05-26
-- Desc:
-- =============================================
CREATE VIEW [dbo].[v_m_member]
AS
    SELECT  IID = 0 ,
            Status = ISNULL(R.Status, 0) ,
            minutes = ISNULL(R.minutes, 0) ,
            Score = ISNULL(R.Score, 0) ,
            ID = ROW_NUMBER() OVER ( ORDER BY myp.ID ASC, M.MID ASC ) ,
            M.[MID] ,
            M.[Name] ,
            M.[IDCard] ,
            M.[IDSN] ,
            [Year] = ISNULL(myp.PID, 0) ,
            M.[Type] ,
            M.[Level] ,
            M.[Phone] ,
            M.[RegDate] ,
            M.RegionCode ,
            R.CommitDate ,
            PayDate = myp.CreateDate
    FROM    Member M
            INNER JOIN dbo.My_Products myp ON myp.MID = M.MID
            LEFT OUTER JOIN Report R ON R.MID = M.MID
                                        AND myp.PID = R.Year
    WHERE   M.Tag = 0
	--AND   M.MID > 10000
           