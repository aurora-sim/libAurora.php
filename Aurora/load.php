<?php
//!	@file Aurora/load.php
//!	@brief This file loads all the code for the Aurora goodness.
//!	@author SignpostMarv

namespace libAurora{

	use Iterator;
	use Countable;
	use ArrayAccess;

//!	abstract iterator, not for any particular class but we don't want to duplicate code.
	abstract class abstractIterator implements Iterator, Countable{
//!	array holds the values of the iterator class
		protected $data = array();

//!	Returns the value at the current cursor position of libAurora::abstractIterator::$data
		public function current(){
			return current($this->data);
		}

//!	Returns the key at the current cursor position of libAurora::abstractIterator::$data
		public function key(){
			return key($this->data);
		}

//!	Advances the cursor on libAurora::abstractIterator::$data
		public function next(){
			next($this->data);
		}

//!	Resets the cursor on libAurora::abstractIterator::$data
		public function rewind(){
			reset($this->data);
		}

//!	Determines if the current cursor position of libAurora::abstractIterator::$data is valid
		public function valid(){
			return $this->key() !== null;
		}

//!	Returns the size of libAurora::abstractIterator::$data
		public function count(){
			return count($this->data);
		}
	}

//!	abstract iterator with ArrayAccess interface
	abstract class abstractIteratorArrayAccess extends abstractIterator implements ArrayAccess{

//!	Determines if a value exists at the specified offset of libAurora::abstractIteratorArrayAccess::$data
		public function offsetExists($offset){
			return isset($this->data[$offset]);
		}

//!	Attempts to get the value at the specified offset of libAurora::abstractIteratorArrayAccess::$data
		public function offsetGet($offset){
			return $this->offsetExists($offset) ? $this->data[$offset] : null;
		}

//!	Attempts to remove the value at the specified offset of libAurora::abstractIteratorArrayAccess::$data
		public function offsetUnset($offset){
			unset($this->data[$offset]);
		}
	}
}

//!	Code from libOMV, taken from visible metadata
namespace OpenMetaverse{

	use InvalidArgumentException;

//!	Transposition of the enum to be used by Aurora::Addon::WebUI::FriendInfo
	class FriendRights{

//!	integer The avatar has no rights
		const None = 0;

//!	integer The avatar can see the online status of the target avatar
		const CanSeeOnline = 1;

//!	integer The avatar can see the location of the target avatar on the map
		const CanSeeOnMap = 2;

//!	integer The avatar can modify the ojects of the target avatar
		const CanModifyObjects = 4;
	}

//!	Transposition of the ParcelCategory enum
	class ParcelCategory{

//!	integer Not an actual category, only used for queries
		const Any = -1;

//!	integer No assigned category
		const None = 0;

//!	integer Linden Infohub or public area (probably counts as GridOperator)
		const Linden = 1;

//!	integer Adult themed area
		const Adult = 2;

//!	integer Arts and Culture
		const Arts = 3;

//!	integer Business
		const Business = 4;

//!	integer Educational
		const Educational = 5;

//!	integer Gaming
		const Gaming = 6;

//!	integer Hangout or Club
		const Hangout = 7;

//!	integer Newcomer friendly
		const Newcomer = 8;

//!	integer Parks and Nature
		const Park = 9;

//!	integer Residential
		const Residential = 10;

//!	integer Shopping
		const Shopping = 11;

//!	integer Not Used?
		const Stage = 12;

//!	integer Other
		const Other = 13;
	}

//!	Transposition of the ParcelStatus enum
	class ParcelStatus{

//!	integer Placeholder
        const None = -1;

//!	integer Parcel is leased (owned) by an avatar or group
        const Leased = 0;

//!	integer Parcel is in process of being leased (purchased) by an avatar or group
        const LeasePending = 1;

//!	integer Parcel has been abandoned back to Governor Linden
        const Abandoned = 2;
	}

//!	Transposition of Vector3
	class Vector3{

//!	float x-axis
//!	@see OpenMetaverse::Vector3::X()
		private $x=0.0;
//!	@return float x-axis
		public function X(){
			return $this->x;
		}

//!	float y-axis
//!	@see OpenMetaverse::Vector3::Y()
		private $y=0.0;
//!	@return float y-axis
		public function Y(){
			return $this->y;
		}

//!	float z-axis
//!	@see OpenMetaverse::Vector3::Z()
		private $z=0.0;
//!	@return float z-axis
		public function Z(){
			return $this->z;
		}

//!	Accepts args to populate new instance axis values
/**
*	@param float $x x-axis
*	@param float $y y-axis
*	@param float $z z-axis
*/
		public function __construct($x=0.0, $y=0.0, $z=0.0){
			if(is_string($x) === true && $y === 0.0 && $z === 0.0 && preg_match('/^\<\s*(\d+|\d*\.\d+)\s*\,\s*(\d+|\d*\.\d+)\s*\,\s*(\d+|\d*\.\d+)\s*\>$/', $x, $matches) === 1){
				$x = (float)$matches[1];
				$y = (float)$matches[2];
				$z = (float)$matches[3];
			}

			if(is_integer($x) === true){
				$x = (float)$x;
			}
			if(is_integer($y) === true){
				$y = (float)$y;
			}
			if(is_integer($z) === true){
				$z = (float)$z;
			}

			if(is_float($x) === false){
				throw new InvalidArgumentException('x-axis should be a float.');
			}else if(is_float($y) === false){
				throw new InvalidArgumentException('y-axis should be a float.');
			}else if(is_float($z) === false){
				throw new InvalidArgumentException('z-axis should be a float.');
			}

			$this->x = $x;
			$this->y = $y;
			$this->z = $z;
		}

//!	Returns the string representation of the vector instance
		public function __toString(){
			return sprintf('<%1$f, %2$f, %3$f>', $this->x, $this->y, $this->z);
		}

//!	Compares two objects
/**
*	@param object $b instance of OpenMetaverse::Vector3
*	@return boolean TRUE if identical, FALSE otherwise
*/
		public function equals(Vector3 $b){
			return (
				$this->X() === $b->X() &&
				$this->Y() === $b->Y() &&
				$this->Z() === $b->Z()
			);
		}
	}

//!	Transposition of AssetType
	class AssetType{

//!	Unknown asset type
        const Unknown = -1;

//!	Texture asset, stores in JPEG2000 J2C stream format
        const Texture = 0;

//!	Sound asset
        const Sound = 1;

//!	Calling card for another avatar
        const CallingCard = 2;

//!	Link to a location in world
        const Landmark = 3;

//!	Collection of textures and parameters that can be worn by an avatar
        const Clothing = 5;

//!	Primitive that can contain textures, sounds, scripts and more
        const Object = 6;

//!	Notecard asset
        const Notecard = 7;

//!	Holds a collection of inventory items
        const Folder = 8;

//!	Root inventory folder
        const RootFolder = 9;

//!	Linden scripting language script
        const LSLText = 10;

//!	LSO bytecode for a script
        const LSLBytecode = 11;

//!	Uncompressed TGA texture
        const TextureTGA = 12;

//!	Collection of textures and shape parameters that can be worn
        const Bodypart = 13;

//!	Trash folder
        const TrashFolder = 14;

//!	Snapshot folder
        const SnapshotFolder = 15;

//!	Lost and found folder
        const LostAndFoundFolder = 16;

//!	Uncompressed sound
        const SoundWAV = 17;

//!	Uncompressed TGA non-square image, not to be used as a texture
        const ImageTGA = 18;

//!	Compressed JPEG non-square image, not to be used as a texture
        const ImageJPEG = 19;

//!	Animation
        const Animation = 20;

//!	Sequence of animations, sounds, chat, and pauses
        const Gesture = 21;

//!	Simstate file
        const Simstate = 22;

//!	Contains landmarks for favorites
        const FavoriteFolder = 23;

//!	Asset is a link to another inventory item
        const Link = 24;

//!	Asset is a link to another inventory folder
        const LinkFolder = 25;

//!	Beginning of the range reserved for ensembles
        const EnsembleStart = 26;

//!	End of the range reserved for ensembles
        const EnsembleEnd = 45;

//!	Folder containing inventory links to wearables and attachments that are part of the current outfit
        const CurrentOutfitFolder = 46;

//!	Folder containing inventory items or links to inventory items of wearables and attachments together make a full outfit
        const OutfitFolder = 47;

//!	Root folder for the folders of type OutfitFolder
        const MyOutfitsFolder = 48;

//!	Linden mesh format
        const Mesh = 49;
	}
}

namespace Aurora\Addon{

//!	This interface exists purely to give client code the ability to detect all Addon-specific exception classes in one go.
//!	The purpose of this behaviour is that instances of Aurora::Addon::Exception will be more or less "safe" for public consumption.
	interface Exception extends \Aurora\Exception{
	}

//!	Addon-specific RuntimeException
	class RuntimeException extends \Aurora\RuntimeException implements Exception{
	}

//!	Addon-specific InvalidArgumentException
	class InvalidArgumentException extends \Aurora\InvalidArgumentException implements Exception{
	}

//!	Addon-specific UnexpectedValueException
	class UnexpectedValueException extends \Aurora\UnexpectedValueException implements Exception{
	}

//!	Addon-specific LengthException
	class LengthException extends \Aurora\LengthException implements Exception{
	}

//!	Addon-specific BadMethodCallException
	class BadMethodCallException extends \Aurora\BadMethodCallException implements Exception{
	}

//!	Determines if a given value is a valid UUID
/**
*	@param mixed $uuid
*	@return TRUE if $uuid is a valid UUID, FALSE otherwise
*/
	function is_uuid($uuid){
		return is_string($uuid) && (preg_match('/^[a-fA-F0-9]{8}\-[a-fA-F0-9]{4}\-[a-fA-F0-9]{4}\-[a-fA-F0-9]{4}\-[a-fA-F0-9]{12}$/', $uuid) == 1);
	}
}

//!	working in the global namespace here
namespace{

//!	Since libAurora.php was split off from webui-gpl, we need to conditionally implement an is_email() function
	if(function_exists('is_email') === false){

//!	Determines if a string is a valid email address
/**
*	@param string $email
*	@return boolean TRUE if $email is a valid email address, FALSE otherwise
*/
		function is_email($email){
			return (is_string($email) && preg_match("/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[a-z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)\b$/", $email) === 1);
		}
	}


	require_once('Framework.php');
	require_once('Services.php');
	require_once('DataManager.php');
	require_once('Addon.php');

//!	Class used for globally-available stuff, because a) define and const only support scalar values, and b) the global operate is ugly.
/**
*	We're making this "final" for semantic reasons.
*	The suggested usage is:
*	Globals::i()->foo = 'bar'; echo Globals::i()->foo;
*	Globals::i()->baz = function(){ return 'bat'; }; echo Globals::i()->baz();
*/
	final class Globals{

//!	We're going to use a singelton method, so we need to make the constructor non-public.
		private function __construct(){}

//!	Returns an instance of Globals via a singelton pattern.
/**
*	Marv's habit here is to use single-letter method names for singelton (i), registry (r) and factory (f) methods.
*	Although in this scheme singelton should probably have "s" instead of "i", what we're returning is the sole instance of a class (emphasis on instance).
*	The instance is held as a static variable inside the method because we want to ensure it's not interfered with by other methods.
*	@return object an instance of Globals
*/
		final public static function i(){
			static $instance;
			if(isset($instance) === false){
				$instance = new self();
			}
			return $instance;
		}

//!	Stores all the values
		private $data = array();

//!	Attempts to set the $value to the specified $name.
/**
*	Do bear in mind that setting a global to null won't stop it from being overwritten. But you shouldn't be doing that anyway.
*	@param string $name
*	@param mixed $value this can be any valid type in PHP, not just scalar values. So feel free to put anonymous functions in.
*/
		final public function __set($name, $value){
			if(isset($this->data[$name]) === true){ // if the name has already been assigned a value,
				if($value !== $this->data[$name]){ // silently fail if the value is identical, throwing an exception otherwise.
					throw new RuntimeException('Cannot overrwite globals');
				}
			}else if(is_string($name) === false || ctype_graph($name) === false){ // this is a lazy (e.g. non-regex) way of checking the name is valid, although is possibly superfluous since if one is doing Globals::i()->foo, the PHP interpreter should ensure the property $name is valid anyway.
				throw new InvalidArgumentException('Global name is invalid.');
			}
			$this->data[$name] = $value;
		}

//!	Attempts to return the value, silently failing by returning null if the property wasn't set.
/**
*	@param string $name
*	@return mixed
*/
		final public function __get($name){ // since there's no other way to put properties in, we're not being paranoid here.
			return isset($this->data[$name]) ? $this->data[$name] : null;
		}

//!	Determine whether or not a property has been set.
/**
*	@param string $name
*	@return TRUE if the property has been set, FALSE otherwise.
*/
		final public function __isset($name){
			return isset($this->data[$name]);
		}

//!	Since globals cannot be overwritten, they cannot be unset either.
/**
*	@param string $name
*/
		final public function __unset($name){
			if(isset($this->data[$name]) === false){ // we will silently fail when values have not been set.
				throw new RuntimeException('Globals cannot be overwritten');
			}
		}

//!	Here's the fun bit. Support for adding anonymous functions.
/**
*	@param string $name
*	@param array $arguments
*	@return mixed returns the value of the callback given the supplied $arguments or throws an exception if the specified $name is not callable for whatever reason.
*/
		final public function __call($name, array $arguments){
			if(isset($this->data[$name]) === false){
				throw new BadMethodCallException('The requested method does not exist');
			}else if(is_callable($this->data[$name]) === false){
				throw new BadMethodCallException('A value was found with the specified name, but it was not callable.');
			}
			return call_user_func_array($this->data[$name], $arguments);
		}

//!	Wraps to Globals::__call()
/**
*	@param string $name
*	@param array $arguments
*	@return mixed wraps to Globals::__call()
*/
		final public static function __callStatic($name, array $arguments){
			if(self::i()->__isset($name) === false){ // The reason why we're not just passing straight to Globals::__call() is in the event Globals::__callStatic() is called directly- one could put an invalid value in $name.
				throw new BadMethodCallException('The requested method does not exist.');
			}
			return self::i()->__call($name, $arguments); // if $name is actually present, assume it was a valid property- otherwise it would not have been able to have been set to begin with- and jump to Globals::__call()
		}
	}
}
?>