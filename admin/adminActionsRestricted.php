<?php
/*
    Copyright (C) 2004-2010 Kestas J. Kuliukas

	This file is part of webDiplomacy.

    webDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    webDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('IN_CODE') or die('This script can not be run by itself.');

/**
 * This class will enable adminActions and adminActionsForum moderator
 * tasks to be performed, but also allow tasks which only admins should
 * be able to perform.
 * This will be included anyway, but the class will only be initialized if
 * the user is an admin.
 *
 * @package Admin
 */
class adminActionsRestricted extends adminActionsForum
{
	public function __construct()
	{
		global $Misc;

		parent::__construct();

		$restrictedActions = array(
			'wipeVariants' => array(
				'name' => 'Clear variant caches',
				'description' => 'Clears out all variant data and cache files, forcing them all to be reinstalled.',
				'params' => array(),
			),
			'clearErrorLogs' => array(
				'name' => 'Clear error logs',
				'description' => 'Clears error log text files.</br>
					<em>ONLY A DEV</em> should run this function, to make sure those logs aren\'t needed for debugging.',
				'params' => array(),
			),
			'clearOrderLogs' => array(
				'name' => 'Clear order logs',
				'description' => 'Clears order log text files.',
				'params' => array(),
			),
			'clearAccessLogs' => array(
				'name' => 'Clear access logs',
				'description' => 'Clears access log table of logs older than 30 days.</br>
					<em>WARNING:</em> Doing this will make catching cheaters difficult or impossible.
					If possible, please take a backup if possible before clearing this table.',
				'params' => array(),
			),
			'clearAdminLogs' => array(
				'name' => 'Clear admin logs',
				'description' => 'Clears admin log table.</br>
					<em>WARNING:</em> Doing this removes the record of Moderator actions from the site.
					This makes referencing past actions impossible, and damages moderator ability to function.
					If possible, please take a backup if possible before clearing this table.',
				'params' => array(),
			),
			'giveModerator' => array(
				'name' => 'Give moderator status',
				'description' => 'Gives moderator status to the specified user ID.',
				'params' => array('userID'=>'User ID'),
			),
			'takeModerator' => array(
				'name' => 'Take moderator status',
				'description' => 'Takes moderator status from the specified user ID.',
				'params' => array('userID'=>'Mod User ID'),
			),
			'giveForumModerator' => array(
				'name' => 'Give forum moderator status',
				'description' => 'Gives forum moderator status to the specified user ID.',
				'params' => array('userID'=>'User ID'),
			),
			'takeForumModerator' => array(
				'name' => 'Take forum moderator status',
				'description' => 'Takes forum moderator status from the specified user ID.',
				'params' => array('userID'=>'Mod User ID'),
			),
			'makeDonator' => array(
				'name' => 'Give donator benefits',
				'description' => 'Give donator benefits (in practical terms this just means opt-out of the distributed processing).<br />
					<em>Only for owner use.</em>',
				'params' => array('userID'=>'User ID'),
			),
			'makeDonatorPlatinum' => array(
				'name' => 'Donator: platinum',
				'description' => 'Give platinum donator marker<br />
					<em>Only for owner use.</em>',
				'params' => array('userID'=>'User ID'),
			),
			'makeDonatorGold' => array(
				'name' => 'Donator: gold',
				'description' => 'Give gold donator marker<br />
					<em>Only for owner use.</em>',
				'params' => array('userID'=>'User ID'),
			),
			'makeDonatorSilver' => array(
				'name' => 'Donator: silver',
				'description' => 'Give silver donator marker<br />
					<em>Only for owner use.</em>',
				'params' => array('userID'=>'User ID'),
			),
			'makeDonatorBronze' => array(
				'name' => 'Donator: bronze',
				'description' => 'Give bronze donator marker<br />
					<em>Only for owner use.</em>',
				'params' => array('userID'=>'User ID'),
			),
			'reprocessGame' => array(
				'name' => 'Reprocess game',
				'description' => 'Returns an active game to the last Diplomacy phase,
					along with that phase\'s orders, and sets the time so that the game will be reprocessed.',
				'params' => array('gameID'=>'Game ID'),
			),
			'notice' => array(
				'name' => 'Toggle site-wide notice',
				'description' => 'Toggle the notice which is displayed in a noticebar across the whole site.',
				'params' => array(),
			),
			'noticeMessage' => array(
				'name' => 'Change notice message',
				'description' => 'Sets the notice which is displayed in a noticebar across the whole site. Sample is: Excused missed turns have been added to all games. See more (<)a href="/contrib/phpBB3/viewtopic.php?f=5&t=1551">here(<)/a(>)(<)/br(>) (<)font color="red"(>)If you are seeing an error on games please clear your browsers cache.(<)/font(>)',
				'params' => array('message'=>'Message'),
			),
			'maintenance' => array(
				'name' => 'Toggle maintenance',
				'description' => 'Toggle maintenance mode, which makes the server inaccessible except to admins
					so changes can be made.',
				'params' => array(),
			),
			'maintenanceMessage' => array(
				'name' => 'Change maintenance message',
				'description' => 'Change the message that is displayed while the site is undergoing maintenance.',
				'params' => array('message'=>'Message'),
			),
			'panicMessage' => array(
				'name' => 'Change panic message',
				'description' => 'Changes the message that is displayed while the site is in panic mode.',
				'params' => array('message'=>'Message'),
			),
			'globalAddTime' => array(
				'name' => 'Add time to all games',
				'description' => 'Add extra time (in hours) to all games, or 0 for the game\'s phase time.',
				'params' => array('timeHours'=>'Extra time (hours)'),
			),
			'backupGame' => array(
				'name' => 'Backup a game',
				'description' => 'Save a game\'s data to the backup tables',
				'params' => array('gameID'=>'Game ID'),
			),
			'restoreGame' => array(
				'name' => 'Restore a game from backups',
				'description' => 'Restores a game from a backup',
				'params' => array('gameID'=>'Game ID'),
			),
			'wipeBackups' => array(
				'name' => 'Wipe game backups',
				'description' => 'Clear out all game backups',
				'params' => array(),
			),
			'checkPausedGames' => array(
				'name' => 'Check paused game process times',
				'description' => 'Sometimes after extending next process times and resetting the last process
						time some games are left with incorrectly set process/pause time values, which causes
						users to have errors when the game gets loaded on their home page / board page.<br />
						Until the cause can be tracked down and resolved this command can find and correct the
						invalid values, but it shouldn\'t be used unless errors are occurring since it may alter
						unaffected games.',
				'params' => array(),
			),
			'recreateUnitDestroyIndex' => array(
				'name' => 'Recreate the destroy unit indexes',
				'description' => 'Refreshes the unit destroy indexes for a certain map ID. This will generally only
					be run if there has been a bug found in the unit destroy index generation code which requires
					the indexes to be recreated.<br />
					Note that this uses the generic installation code, so if there are any variant-specific modifications
					running this may give unpredictable results. Please confirm with the variant maintainer before
					using this admin action.',
				'params' => array('mapID'=>'Map ID'),
			),
			'recalculateRR' => array(
				'name' => 'Recalculate reliability ratings',
				'description' => 'Updates the reliability ratings for all users.',
				'params' => array()
			),
			'resetLastProcessTime' => array(
				'name' => 'Reset last process time',
				'description' => 'Once the reason for the period of no processing is known, and it\'s safe to reenable
					game processing, the last process time can be reset here.<br />
					<em>Only a dev</em> should run this function after they ensure the issue has been fixed.',
				'params' => array(),
			),
			'banIP' => array(
				'name' => 'Ban an IP',
				'description' => 'Bans a certain IP address.<br />
					Note: Doesn\'t work.',
				'params' => array('IP'=>'IP address (xxx.xxx.xxx.xxx)'),
			),
			'unCrashGames' => array(
				'name' => 'Uncrash games',
				'description' => 'Uncrashes all crashed games except the games specified (if any).<br />
					<em>ONLY A DEV</em> should run this function. The reason for the crash needs to be found out before the games are uncrashed.',
				'params' => array('excludeGameIDs'=>'Except Game ID list'),
            ),
			'addApiKey' => array(
				'name' => 'API - Add an API key for a user',
				'description' => 'Associate an API key to a user.',
				'params' => array('userID'=>'User ID'),
			),
			'deleteApiKey' => array(
				'name' => 'API - Delete all API keys for a user',
				'description' => 'Remove all API keys assiociated with a user.',
				'params' => array('userID'=>'User ID'),
			),
			'setApiPermission' => array(
				'name' => 'API - Set API key permission',
				'description' => 'Set an API permission for a user. (getStateOfAllGames, submitOrdersForUserInCD, listGamesWithPlayersInCD)',
				'params' => array('userID'=>'User ID', 'permissionName' => 'Permission name', 'permissionValue' => 'Permission value ("Yes" or "No").'),
			),
			'showApiKeys' => array(
				'name' => 'API - Show API key and permissions for a user',
				'description' => 'Display API key and permissions for a user.',
				'params' => array('userID'=>'User ID'),
			)
		);

		adminActions::$actions = array_merge(adminActions::$actions, $restrictedActions);
	}
	public function wipeVariants(array $params) {

		foreach(Config::$variants as $variantID=>$variantName)
			libVariant::wipe($variantName);

		return l_t('All variants wiped.');
	}
	public function backupGameConfirm(array $params)
	{
		global $DB;

		require_once(l_r('objects/game.php'));
		$Variant=libVariant::loadFromGameID($params['gameID']);
		$Game = $Variant->Game($params['gameID']);

		return l_t('Are you sure you want to backup this game?');
	}
	public function backupGame(array $params)
	{
		global $DB;

		require_once(l_r('objects/game.php'));

		$gameID = (int)$params['gameID'];

		$DB->sql_put("BEGIN");

		processGame::backupGame($gameID);

		return l_t('Game backed up');
	}
	public function restoreGameConfirm(array $params)
	{
		global $DB;

		require_once(l_r('objects/game.php'));
		$Variant=libVariant::loadFromGameID($params['gameID']);
		$Game = $Variant->Game($params['gameID']);

		return l_t('Are you sure you want to restore this game?');
	}
	public function restoreGame(array $params)
	{
		global $DB;

		require_once(l_r('gamemaster/game.php'));

		$gameID = (int)$params['gameID'];

		$DB->sql_put("BEGIN");

		processGame::restoreGame($gameID);

		return l_t('Game restored');
	}
	public function wipeBackupsConfirm(array $params)
	{
		return l_t('Are you sure you want to wipe backups?');
	}
	public function wipeBackups(array $params)
	{
		global $DB;

		require_once(l_r('gamemaster/game.php'));

		processGame::wipeBackups();

		return l_t('Backups wiped');
	}

	public function globalAddTime(array $params)
	{
		global $DB;

		$timeHours=$params['timeHours'];

		/*
		 * Paused/Finished - No change
		 *
		 * 	Pre-game -
		 * 		processTime-time() > phaseMinutes*60 - No change, a scheduled time is set
		 * 		processTime-time() <= phaseMinutes*60 - Add the requested time
		 * 	Active -
		 * 		phaseMinutes*60 < downtime - Pause
		 * 		phaseMinutes*60 >= downtime - Add
		 * 			downtime>phaseMinutes*60Add time up to phaseMinutes*60
		 */
		/*if($timeHours==0)
		{
			$DB->sql_put('UPDATE wD_Games SET processTime = processTime+phaseMinutes*60
				WHERE processStatus="Not-processing" AND NOT phase="Finished" AND
					(
					( phase="Pre-game" AND processTime-'.time().'<=phaseMinutes*60 )
					OR (NOT phase="Pre-game" AND '.round($timeHours*60*60).'<=phaseMinutes*60 )
					)');

			$DB->sql_put('UPDATE wD_Games SET processStatus="Paused", processTime=NULL,
					pausedTimeRemaining=IF((processTime-'.time().')<=0, phaseMinutes*60, processTime-'.time().')
				WHERE processStatus="Not-processing" AND NOT phase="Finished" AND
					NOT phase="Pre-game" AND '.round($timeHours*60*60).'>phaseMinutes*60 )');
		}
		else
		{*/
			$DB->sql_put('UPDATE wD_Games SET processTime = processTime+'.round($timeHours*60*60).'
				WHERE processStatus="Not-processing" AND NOT phase="Finished" AND
					(
					( phase="Pre-game" AND processTime<=phaseMinutes*60+'.time().' )
					OR (NOT phase="Pre-game" AND '.round($timeHours*60*60).'<=phaseMinutes*60 )
					)');

			$DB->sql_put('UPDATE wD_Games SET processStatus="Paused", processTime=NULL,
					pauseTimeRemaining=IF((processTime-'.time().')<=0, phaseMinutes*60, processTime-'.time().')
				WHERE processStatus="Not-processing" AND NOT phase="Finished" AND
					NOT phase="Pre-game" AND '.round($timeHours*60*60).'>phaseMinutes*60');
			$DB->sql_put("COMMIT");
		//}

		if($timeHours==0)
			return l_t('All game process times have been set to their phase time');
		else
			return l_t('All games have had %s hours added to them',$timeHours);
	}

	public function maintenance(array $params)
	{
		global $Misc;

		$Misc->Maintenance = 1-$Misc->Maintenance;
		$Misc->write();

		return l_t('Maintenance mode '.($Misc->Maintenance?'turned on':'turned off'));
	}

	public function maintenanceMessage(array $params)
	{
		global $DB;
		$message = $params['message'];
		$message = $DB->escape($message, $htmlAllowed=true);
		$DB->sql_put("UPDATE wD_Config SET message='".$message."' WHERE name = 'Maintenance'");
		return l_t('The maintenance message has been updated.');
	}

	public function notice(array $params)
	{
		global $Misc;

		$Misc->Notice = 1-$Misc->Notice;
		$Misc->write();

		return l_t('Site-wide notice '.($Misc->Notice?'turned on':'turned off'));
	}

	public function noticeMessage(array $params)
	{
		global $DB;
		$message = $params['message'];
		$message = $DB->escape($message, $htmlAllowed=true);
		$DB->sql_put("UPDATE wD_Config SET message='".$message."' WHERE name = 'Notice'");
		return l_t('The site-wide notice message has been updated.');
	}

	public function panicMessage(array $params)
	{
		global $DB;
		$message = $params['message'];
		$message = $DB->escape($message, $htmlAllowed=true);
		$DB->sql_put("UPDATE wD_Config SET message='".$message."' WHERE name = 'Panic'");
		return l_t('The panic message has been updated.');
	}

	public function clearErrorLogs(array $params)
	{
		global $Misc;

		$oldCount = $Misc->ErrorLogs;

		libError::clear();

		return l_t('The error logs were cleared, %s files deleted.',$oldCount);
	}

	public function clearOrderLogs(array $params)
	{
		$logDir = Config::orderlogDirectory();

		if ( ! is_dir($logDir) or ! ( $handle = opendir($logDir) ) )
		{
			throw new Exception(l_t("Could not open log directory"));
		}

		$logs = array();
		$exclude = array('.','..','index.html');
		while ( false !== ( $file = readdir($handle) ) )
		{
			if( in_array($file, $exclude) ) continue;

			$logs[] = $file;
		}
		closedir($handle);

		$i=0;
		foreach ( $logs as $log )
		{
			unlink($logDir.'/'.$log);
			$i++;
		}

		return l_t('The order logs were cleared, %s files deleted.',$i);
	}

	public function wipeDATCTestGame(array $params)
	{
		global $DB;

		require_once('gamemaster/game.php');

		$DB->sql_put("BEGIN");
		list($gameID) = $DB->sql_row("SELECT id FROM wD_Games WHERE name='DATC-Adjudicator-Test'");
		processGame::eraseGame($gameID);
		$DB->sql_put("COMMIT");

		return l_t("DATC test game and associated data removed.");
	}
	public function clearAccessLogs(array $params)
	{
		global $DB;

		list($i) = $DB->sql_row("SELECT COUNT(userID) FROM wD_AccessLog WHERE DATEDIFF(CURRENT_DATE, lastRequest) > 30");

		$DB->sql_put("DELETE FROM wD_AccessLog WHERE DATEDIFF(CURRENT_DATE, lastRequest) > 30");

		$DB->sql_put("OPTIMIZE TABLE wD_AccessLog");

		return l_t('Old access logs cleared; %s records deleted.',$i);
	}

	public function clearAdminLogs(array $params)
	{
		global $DB;

		$DB->sql_put("BEGIN");

		list($i) = $DB->sql_row("SELECT COUNT(userID) FROM wD_AdminLog");

		$DB->sql_put("DELETE FROM wD_AdminLog");

		$DB->sql_put("COMMIT");

		return l_t('The admin log was cleared; %s records deleted.',$i);
	}

	public function giveModerator(array $params)
	{
		global $DB;

		$userID = (int)$params['userID'];

		$modUser = new User($userID);

		if( $modUser->type['Moderator'] )
			throw new Exception(l_t("This user is already a moderator"));

		$DB->sql_put(
			"UPDATE wD_Users SET type = CONCAT_WS(',',type,'Moderator') WHERE id = ".$userID
		);

		return l_t('This user was given moderator status.');
	}

	public function takeModerator(array $params)
	{
		global $DB;

		$userID = (int)$params['userID'];

		$modUser = new User($userID);

		if( ! $modUser->type['Moderator'] )
			throw new Exception(l_t("This user isn't a moderator"));

		$DB->sql_put(
			"UPDATE wD_Users SET type = REPLACE(type,'Moderator','') WHERE id = ".$userID
		);

		return l_t('This user had their moderator status taken.');
	}

	public function giveForumModerator(array $params)
	{
		global $DB;

		$userID = (int)$params['userID'];

		$modUser = new User($userID);

		if( $modUser->type['ForumModerator'] )
			throw new Exception(l_t("This user is already a moderator"));

		$DB->sql_put(
			"UPDATE wD_Users SET type = CONCAT_WS(',',type,'ForumModerator') WHERE id = ".$userID
		);

		return l_t('This user was given forum moderator status.');
	}

	public function takeForumModerator(array $params)
	{
		global $DB;

		$userID = (int)$params['userID'];

		$modUser = new User($userID);

		if( ! $modUser->type['ForumModerator'] )
			throw new Exception(l_t("This user isn't a forum moderator"));

		$DB->sql_put(
			"UPDATE wD_Users SET type = REPLACE(type,'ForumModerator','') WHERE id = ".$userID
		);

		return l_t('This user had their forum moderator status taken.');
	}

	public function checkPausedGames(array $params)
	{
		global $DB;

		list($minTime) = $DB->sql_row(
			"SELECT MIN(processTime) FROM wD_Games
			WHERE NOT phase='Finished' AND processStatus='Not-processing'
				AND processTime > 123456789"
		);

		$affected = 0;
		$DB->sql_put(
			"UPDATE `wD_Games` SET
				processTime=NULL,
				pauseTimeRemaining=phaseMinutes*60
			WHERE pauseTimeRemaining IS NULL AND processStatus='Paused'"
		);
		$affected += $DB->last_affected();

		$DB->sql_put(
			"UPDATE `wD_Games` SET
				processTime=(".$minTime."+phaseMinutes*60),
				pauseTimeRemaining=NULL
			WHERE processTime IS NULL AND processStatus='Not-processing'"
		);
		$affected += $DB->last_affected();

		return l_t('Any invalid next-process/pause-length times have been reset; %s game(s) affected.',$affected);
	}

	public function reprocessGame(array $params)
	{
		global $DB, $Game;

		$gameID = (int)$params['gameID'];

		/*
		 * - Check that the game is still active and can be turned back
		 *
		 * - Delete current turn values
		 * - Calculate the turn being moved back to
		 *
		 * - Move the old MovesArchive back to Units
		 * - Move the old MovesArchive (JOIN Units) back to Orders
		 * - Move the old TerrStatusArchive (JOIN Units) back to TerrStatus
		 *
		 * - Update the game turn, phase and next process time
		 *
		 * - Delete Archive values if we have moved back a turn
		 * - Remove the invalid maps in the mapstore
		 */
		$DB->sql_put("BEGIN");

		require_once(l_r('gamemaster/game.php'));
		$Variant=libVariant::loadFromGameID($gameID);
		$Game = $Variant->processGame($gameID);

		$oldPhase = $Game->phase;
		$oldTurn = $Game->turn;

		// - Check that the game is still active and can be turned back
		if ( $Game->turn < 1 )
		{
			throw new Exception(l_t('This game cannot be turned back; it is new or is finished.'));
		}

		// - Delete current turn values
		$DB->sql_put("DELETE FROM wD_Units WHERE gameID = ".$Game->id);
		$DB->sql_put("DELETE FROM wD_TerrStatus WHERE gameID = ".$Game->id);
		$DB->sql_put("DELETE FROM wD_Orders WHERE gameID = ".$Game->id);

		// - Calculate the turn being moved back to
		$lastTurn = ( ( $Game->phase == 'Diplomacy' ) ? $Game->turn-1 : $Game->turn );


		// Begin moving the archives back
		{
			// - Move the old MovesArchive back to Units
			$DB->sql_put("INSERT INTO wD_Units ( type, terrID, countryID, gameID )
						SELECT unitType, terrID, countryID, gameID FROM wD_MovesArchive
						WHERE gameID = ".$Game->id." AND turn = ".$lastTurn."
							/* Make sure only the Diplomacy phase unit positions are used */
							AND type IN ( 'Hold', 'Move', 'Support hold', 'Support move', 'Convoy' )");

			// - Move the old MovesArchive (JOIN Units) back to Orders
			$DB->sql_put("INSERT INTO wD_Orders (gameID, countryID, type, toTerrID, fromTerrID, viaConvoy, unitID)
						SELECT m.gameID, m.countryID, m.type, m.toTerrID, m.fromTerrID, m.viaConvoy, u.id
						FROM wD_MovesArchive m INNER JOIN wD_Units u ON ( u.terrID = m.terrID AND u.gameID = m.gameID )
						WHERE m.gameID = ".$Game->id." AND m.turn = ".$lastTurn."
							/* Make sure only the Diplomacy phase unit positions are used */
							AND m.type IN ( 'Hold', 'Move', 'Support hold', 'Support move', 'Convoy' )");

			// - Move the old TerrStatusArchive back to TerrStatus
			$DB->sql_put("INSERT INTO wD_TerrStatus ( terrID, standoff, gameID, countryID, occupyingUnitID )
						SELECT t.terrID, t.standoff, t.gameID, t.countryID, u.id
						FROM wD_TerrStatusArchive t
							LEFT JOIN wD_Units u
							ON ( ".$Game->Variant->deCoastCompare('t.terrID','u.terrID')." AND u.gameID = t.gameID )
						WHERE t.gameID = ".$Game->id." AND t.turn = ".$lastTurn);
		}

		// - Update the game turn, phase and next process time
		$DB->sql_put("UPDATE wD_Games
					SET turn = ".$lastTurn.", phase = 'Diplomacy', gameOver='No', processTime = phaseMinutes*60+".time().",
						processStatus='Not-processing', pauseTimeRemaining=NULL
					WHERE id = ".$Game->id);

		$DB->sql_put("UPDATE wD_Members SET votes='', orderStatus='',
			status=IF(status='Won' OR status='Survived' OR status='Drawn' OR status='Playing',
				'Playing',
				IF(status='Resigned' OR status='Left','Left','Defeated')
			)
			WHERE gameID = ".$Game->id);

		$DB->sql_put("COMMIT");

		// - Delete Archive values if we have moved back a turn
		$DB->sql_put("DELETE FROM wD_TerrStatusArchive WHERE gameID = ".$Game->id." AND turn = ".$lastTurn);
		$DB->sql_put("DELETE FROM wD_MovesArchive WHERE gameID = ".$Game->id." AND turn = ".$lastTurn);

		// - Remove the invalid maps in the mapstore
		$Game->load();
		Game::wipeCache($Game->id);

		libGameMessage::send(0, 'GameMaster', l_t('This game has been moved back to %s',$Game->datetxt($lastTurn)), $Game->id);

		return l_t('This game was moved from %s, %s back to Diplomacy, %s, and is ready to be reprocessed.',
			$oldPhase,$Game->datetxt($oldTurn),$Game->datetxt($lastTurn));
	}

	public function recreateUnitDestroyIndex(array $params)
	{
		global $DB;

		$mapID = (int)$params['mapID'];

		require_once("variants/install.php");

		InstallTerritory::loadExistingTerritories($mapID);

		// Generate the SQL before wiping & reinserting it
		$unitDestroyIndexRecreateSQL = InstallTerritory::unitDestroyIndexSQL($mapID);

		$DB->sql_put("BEGIN");

		list($entriesBefore) = $DB->sql_row("SELECT COUNT(*) FROM wD_UnitDestroyIndex WHERE mapID = ".$mapID);

 		$DB->sql_put("DELETE FROM wD_UnitDestroyIndex WHERE mapID = ".$mapID);

 		$DB->sql_put($unitDestroyIndexRecreateSQL);

 		list($entriesAfter) = $DB->sql_row("SELECT COUNT(*) FROM wD_UnitDestroyIndex WHERE mapID = ".$mapID);

		$DB->sql_put("COMMIT");

		return l_t('The unit destroy indexes were recreated for map ID #%s ; there were %s entries before and there are currently %s entries.', $mapID, $entriesBefore, $entriesAfter);
	}
	public function recalculateRR(array $params)
	{
		require_once(l_r('gamemaster/gamemaster.php'));
		libGameMaster::updateReliabilityRating(true);
		return l_t("Reliability Ratings have been recalculated");
	}
	private function makeDonatorType(array $params, $type='') 
	{
		global $DB;

		$userID = (int)$params['userID'];

		$DB->sql_put("UPDATE wD_Users SET type = CONCAT_WS(',',type,'Donator".$type."') WHERE id = ".$userID);

		return l_t('User ID %s given donator status.',$userID);
	}

	public function makeDonator(array $params)
	{
		return $this->makeDonatorType($params);
	}
	public function makeDonatorPlatinum(array $params)
	{
		return $this->makeDonatorType($params,'Platinum');
	}
	public function makeDonatorGold(array $params)
	{
		return $this->makeDonatorType($params,'Gold');
	}
	public function makeDonatorSilver(array $params)
	{
		return $this->makeDonatorType($params,'Silver');
	}
	public function makeDonatorBronze(array $params)
	{
		return $this->makeDonatorType($params,'Bronze');
	}
	public function banIP(array $params)
	{
		User::banIP(ip2long($ip));
	}
	public function resetLastProcessTime(array $params)
	{
		global $Misc;

		$Misc->LastProcessTime = time();
		$Misc->write();

		return l_t('Last process time reset');
	}
	public function unCrashGames(array $params)
	{
		global $DB;

		require_once(l_r('gamemaster/game.php'));

		$excludeGameIDs = explode(',', $params['excludeGameIDs']);

		foreach($excludeGameIDs as $index=>$gameID)
		{
			$gameID = (int)$gameID;
			$excludeGameIDs[$index] = $gameID;
		}
		$excludeGameIDs = implode(',', $excludeGameIDs);

		$tabl = $DB->sql_tabl(
			"SELECT * FROM wD_Games WHERE processStatus = 'Crashed' ".( $excludeGameIDs ? "AND id NOT IN (".$excludeGameIDs.")" : "" )." FOR UPDATE"
		);
		$count=0;
		while($row=$DB->tabl_hash($tabl))
		{
			$count++;

			$Variant=libVariant::loadFromVariantID($row['variantID']);
			$Game = $Variant->processGame($row);

			if( $Game->phase == 'Finished' )
			{
				$DB->sql_put("UPDATE wD_Games SET processStatus = 'Not-processing', pauseTimeRemaining=NULL, processTime = ".time()." WHERE id = ".$Game->id);
				continue;
			}

			if ( $Game->phaseMinutes < 12*60 )
			{
				if( $Game->phase == 'Pre-game' )
				{
					$newTimeDetails = "";
					$DB->sql_put("UPDATE wD_Games SET processStatus = IF(pauseTimeRemaining IS NULL,'Not-processing','Paused') WHERE id = ".$Game->id);
				}
				else
				{
					$newTimeDetails = l_t(", and the game has been paused, since it's a fast game, to give players a chance to regroup");
					$Game->processStatus = 'Not-processing';
					$Game->togglePause();
				}
			}
			else
			{
				$newTimeDetails = l_t(", and the game's next process time has been reset");
				$DB->sql_put("UPDATE wD_Games SET processStatus = 'Not-processing', processTime = ".time()." + 60*phaseMinutes, pauseTimeRemaining=NULL WHERE id = ".$Game->id);
			}

			$Game->Members->send('No',l_t("This game has been uncrashed%s. Thanks for your patience.",$newTimeDetails));
		}

		/* Simpler, but doesn't accomodate live games well
		$DB->sql_put(
			"UPDATE wD_Games SET processStatus = 'Not-processing', processTime = ".time()." + 60*phaseMinutes
			WHERE AND processStatus = 'Crashed' ".( $excludeGameIDs ? "AND id NOT IN (".$excludeGameIDs.")" : "" )
		);*/

		$details = l_t('All crashed games were un-crashed');
		if ( $excludeGameIDs )
			$details .= l_t(', except: %s',$excludeGameIDs);
		$details .= l_t('. %s games in total.',$count);

		return $details;
	}

	public function addApiKey($params) {
		global $DB;

		// Generating API Key
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < 80; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}

		$userID = intval($params['userID']);
		$apiKey = strval($randomString);
		$row = $DB->sql_hash('SELECT COUNT(userID) AS hasUserID FROM wD_ApiKeys WHERE userID = '.$userID);
		if ($row['hasUserID'])
			throw new Exception(l_t('An API key is already associated to user ID %s.', $userID));
		$row = $DB->sql_hash('SELECT COUNT(apiKey) AS hasApiKey FROM wD_ApiKeys WHERE apiKey = '.$userID);
		if ($row['hasApiKey'])
			throw new Exception(l_t('This API key is already associated with a user ID.'));
		$DB->sql_put('INSERT INTO wD_ApiKeys (userID, apiKey) VALUES ('.$userID.', "'.$apiKey.'")');
		$DB->sql_put("COMMIT");
		return l_t('API key %s successfully added for user ID %s.', $randomString, $userID);
	}
	public function deleteApiKey($params) {
		global $DB;
		$userID = intval($params['userID']);
		$DB->sql_put('DELETE FROM wD_ApiPermissions WHERE userID = '.$userID);
		$DB->sql_put('DELETE FROM wD_ApiKeys WHERE userID = '.$userID);
		$DB->sql_put("COMMIT");
		return l_t('API key(s) removed for user ID %s.', $userID);
	}
	public function setApiPermission($params) {
		global $DB;
		$userID = intval($params['userID']);
		$permissionName = strval($params['permissionName']);
		$permissionValue = strval($params['permissionValue']);
		$currentPermissions = array(
			'getStateOfAllGames',
			'submitOrdersForUserInCD',
			'listGamesWithPlayersInCD',
		);
		if (!in_array($permissionName, $currentPermissions))
			throw new Exception('Unknown permission "'.$permissionName.'". Should be one of: ['.implode(', ', $currentPermissions).'].');
		if (!in_array($permissionValue, array('Yes', 'No')))
			throw new Exception('Invalid permission value "'.$permissionValue.'". Should be either "Yes" or "No".');
		$row = $DB->sql_hash('SELECT COUNT(userID) AS hasUserID FROM wD_ApiKeys WHERE userID = '.$userID);
		if (!$row['hasUserID'])
			throw new Exception(l_t('No API key for user %s. You should create an API key for this user before setting permissions for him.', $userID));
		$row = $DB->sql_hash('SELECT COUNT(userID) AS hasPermissionsEntry FROM wD_ApiPermissions WHERE userID = '.$userID);
		if ($row['hasPermissionsEntry']) {
			$DB->sql_put("UPDATE wD_ApiPermissions SET $permissionName = '$permissionValue' WHERE userID = $userID;");
		} else {
			$DB->sql_put("INSERT INTO wD_ApiPermissions (userID, $permissionName) VALUES ($userID, '$permissionValue');");
		};
		$DB->sql_put("COMMIT");
		return l_t('Permissions successfully set.');
	}
	public function showApiKeys($params) {
		global $DB;
		$userID = intval($params['userID']);
		$row = $DB->sql_hash("
		SELECT
		       k.apiKey,
		       IFNULL(p.getStateOfAllGames, 'No') as getStateOfAllGames,
		       IFNULL(p.listGamesWithPlayersInCD, 'No') as listGamesWithPlayersInCD,
		       IFNULL(p.submitOrdersForUserInCD, 'No') as submitOrdersForUserInCD
		FROM wD_ApiKeys AS k
		LEFT JOIN wD_ApiPermissions AS p ON (k.userID = p.userID)
		WHERE k.userID = ".$userID."
		");
		if (!$row)
			return l_t('No api Key for user %s.', $userID);
		return "
		<div><strong>User ID</strong>: ".$userID."</div>
		<div><strong>API key</strong>: ".$row['apiKey']."</div>
		<div><strong>getStateOfAllGames</strong>: ".$row['getStateOfAllGames']."</div>
		<div><strong>listGamesWithPlayersInCD</strong>: ".$row['listGamesWithPlayersInCD']."</div>
		<div><strong>submitOrdersForUserInCD</strong>: ".$row['submitOrdersForUserInCD']."</div>
		";
	}
}

?>
